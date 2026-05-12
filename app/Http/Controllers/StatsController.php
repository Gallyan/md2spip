<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

/**
 * Renders the public usage statistics dashboard.
 */
final class StatsController extends Controller
{
    public function __invoke(): View
    {
        $disk = Storage::disk('stats');

        /** @var array<string, array{sessions?: int, conversions?: int, copies?: int, total_chars?: int}> $raw */
        $raw = $disk->exists('stats.json')
            ? (json_decode((string) $disk->get('stats.json'), true) ?: [])
            : [];

        ksort($raw);

        $totals = ['sessions' => 0, 'conversions' => 0, 'copies' => 0, 'total_chars' => 0];

        foreach ($raw as $day) {
            $totals['sessions'] += $day['sessions'] ?? 0;
            $totals['conversions'] += $day['conversions'] ?? 0;
            $totals['copies'] += $day['copies'] ?? 0;
            $totals['total_chars'] += $day['total_chars'] ?? 0;
        }

        $recentDates = array_slice(array_keys($raw), -30);
        $daily = [];
        foreach ($recentDates as $date) {
            $daily[] = [
                'date' => $date,
                'sessions' => $raw[$date]['sessions'] ?? 0,
                'conversions' => $raw[$date]['conversions'] ?? 0,
                'copies' => $raw[$date]['copies'] ?? 0,
            ];
        }

        return view('stats', [
            'totals' => $totals,
            'daily' => $daily,
        ]);
    }
}
