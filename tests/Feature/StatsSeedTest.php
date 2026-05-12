<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StatsSeedTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('stats');
    }

    public function test_seed_creates_stats_file_with_expected_keys(): void
    {
        $exitCode = Artisan::call('stats:seed', ['--fresh' => true, '--days' => 3]);

        $this->assertSame(0, $exitCode);

        $stats = json_decode((string) Storage::disk('stats')->get('stats.json'), true);

        $this->assertCount(3, $stats);
        foreach ($stats as $day) {
            $this->assertArrayHasKey('sessions', $day);
            $this->assertArrayHasKey('conversions', $day);
            $this->assertArrayHasKey('copies', $day);
            $this->assertArrayHasKey('total_chars', $day);
        }
    }

    public function test_seed_fresh_overwrites_existing_data(): void
    {
        Storage::disk('stats')->put('stats.json', json_encode([
            '2020-01-01' => ['sessions' => 999, 'conversions' => 0, 'copies' => 0, 'total_chars' => 0],
        ], JSON_THROW_ON_ERROR));

        $exitCode = Artisan::call('stats:seed', ['--fresh' => true, '--days' => 2]);
        $this->assertSame(0, $exitCode);

        $stats = json_decode((string) Storage::disk('stats')->get('stats.json'), true);

        $this->assertArrayNotHasKey('2020-01-01', $stats);
        $this->assertCount(2, $stats);
    }

    public function test_seed_without_fresh_keeps_existing_data(): void
    {
        Storage::disk('stats')->put('stats.json', json_encode([
            '2020-01-01' => ['sessions' => 999, 'conversions' => 0, 'copies' => 0, 'total_chars' => 0],
        ], JSON_THROW_ON_ERROR));

        $exitCode = Artisan::call('stats:seed', ['--days' => 2]);
        $this->assertSame(0, $exitCode);

        $stats = json_decode((string) Storage::disk('stats')->get('stats.json'), true);

        $this->assertArrayHasKey('2020-01-01', $stats);
        $this->assertSame(999, $stats['2020-01-01']['sessions']);
    }

    public function test_seed_refuses_in_production(): void
    {
        app()->detectEnvironment(fn () => 'production');

        $exitCode = Artisan::call('stats:seed');

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('Refused in production.', Artisan::output());
    }
}
