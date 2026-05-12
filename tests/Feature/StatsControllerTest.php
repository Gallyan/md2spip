<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StatsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('stats');
    }

    public function test_stats_page_loads_successfully(): void
    {
        $response = $this->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('Statistiques d\'usage');
    }

    public function test_stats_page_is_noindex(): void
    {
        $response = $this->get('/stats');

        $response->assertSee('noindex', false);
    }

    public function test_stats_page_shows_empty_state_when_no_data(): void
    {
        $response = $this->get('/stats');

        $response->assertSee('Pas encore de données', false);
    }

    public function test_stats_page_renders_chart_when_data_exists(): void
    {
        $today = date('Y-m-d');
        Storage::disk('stats')->put('stats.json', json_encode([
            $today => [
                'sessions' => 42,
                'conversions' => 18,
                'copies' => 25,
                'total_chars' => 1234,
            ],
        ], JSON_THROW_ON_ERROR));

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('<svg', false);
        $response->assertDontSee('Pas encore de données', false);
    }

    public function test_stats_page_aggregates_totals_across_days(): void
    {
        Storage::disk('stats')->put('stats.json', json_encode([
            '2026-05-01' => ['sessions' => 100, 'conversions' => 40, 'copies' => 60, 'total_chars' => 5000],
            '2026-05-02' => ['sessions' => 200, 'conversions' => 80, 'copies' => 120, 'total_chars' => 10000],
        ], JSON_THROW_ON_ERROR));

        $response = $this->get('/stats');

        $response->assertSee('300', false); // sessions
        $response->assertSee('120', false); // conversions
        $response->assertSee('180', false); // copies
    }
}
