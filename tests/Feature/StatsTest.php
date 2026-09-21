<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\Stats;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StatsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('stats');
    }

    public function test_increments_accumulate(): void
    {
        Stats::increment('copies');
        Stats::increment('copies');
        Stats::increment('total_chars', 42);

        $today = date('Y-m-d');

        $this->assertSame(['copies' => 2, 'total_chars' => 42], Stats::read()[$today]);
    }

    public function test_writes_replace_the_file_without_leaving_a_temporary_one(): void
    {
        Stats::write(['2026-01-01' => ['sessions' => 3]]);
        Stats::increment('sessions');

        $this->assertSame(3, Stats::read()['2026-01-01']['sessions']);
        $this->assertFalse(Storage::disk('stats')->exists('stats.json.tmp'));
    }

    public function test_increment_keeps_history_written_by_another_writer(): void
    {
        Stats::write(['2026-01-01' => ['sessions' => 5]]);

        Stats::increment('sessions');

        $stats = Stats::read();
        $this->assertSame(5, $stats['2026-01-01']['sessions']);
        $this->assertSame(1, $stats[date('Y-m-d')]['sessions']);
    }
}
