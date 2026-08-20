<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\Stats;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('stats:seed {--days=30 : Number of days to generate} {--fresh : Overwrite the existing file}')]
#[Description('Generates fake usage statistics for the /stats dashboard.')]
final class StatsSeed extends Command
{
    public function handle(): int
    {
        if (app()->environment('production')) {
            $this->error('Refused in production.');

            return self::FAILURE;
        }

        /** @var int $days */
        $days = (int) $this->option('days');
        $fresh = (bool) $this->option('fresh');

        $stats = $fresh || ! Stats::exists()
            ? []
            : Stats::read();

        for ($i = $days - 1; $i >= 0; $i--) {
            $ts = strtotime("-{$i} days") ?: time();
            $date = date('Y-m-d', $ts);
            $weekday = (int) date('N', $ts);
            $weekendFactor = $weekday >= 6 ? 0.4 : 1.0;

            $sessions = (int) round(random_int(30, 180) * $weekendFactor);
            $conversions = (int) round($sessions * (random_int(30, 60) / 100));
            $copies = (int) round($conversions * (random_int(110, 200) / 100));
            $totalChars = $copies * random_int(200, 3500);

            $stats[$date] = [
                'sessions' => $sessions,
                'conversions' => $conversions,
                'copies' => $copies,
                'total_chars' => $totalChars,
            ];
        }

        ksort($stats);
        Stats::write($stats);

        $this->info("Stats generated for {$days} day(s) → ".Stats::path());

        return self::SUCCESS;
    }
}
