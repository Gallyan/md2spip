<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Stats;
use Illuminate\Contracts\View\View;

/**
 * Renders the public usage statistics dashboard.
 */
final class StatsController extends Controller
{
    public function __invoke(): View
    {
        $raw = Stats::read();

        ksort($raw);

        $totals = ['sessions' => 0, 'conversions' => 0, 'copies' => 0, 'total_chars' => 0];

        foreach ($raw as $day) {
            $totals['sessions'] += $day['sessions'] ?? 0;
            $totals['conversions'] += $day['conversions'] ?? 0;
            $totals['copies'] += $day['copies'] ?? 0;
            $totals['total_chars'] += $day['total_chars'] ?? 0;
        }

        $daily = [];

        if ($raw !== []) {
            $start = now()->subDays(29)->startOfDay();
            for ($i = 0; $i < 30; $i++) {
                $date = $start->copy()->addDays($i)->format('Y-m-d');
                $daily[] = [
                    'date' => $date,
                    'sessions' => $raw[$date]['sessions'] ?? 0,
                    'conversions' => $raw[$date]['conversions'] ?? 0,
                    'copies' => $raw[$date]['copies'] ?? 0,
                ];
            }
        }

        return view('stats', [
            'totals' => $totals,
            'daily' => $daily,
        ]);
    }
}
