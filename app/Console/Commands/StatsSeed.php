<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('stats:seed {--days=30 : Nombre de jours à générer} {--fresh : Écrase le fichier existant}')]
#[Description('Génère des statistiques d\'usage fictives pour le dashboard /stats.')]
final class StatsSeed extends Command
{
    public function handle(): int
    {
        if (app()->environment('production')) {
            $this->error('Refusé en production.');

            return self::FAILURE;
        }

        /** @var int $days */
        $days = (int) $this->option('days');
        $fresh = (bool) $this->option('fresh');

        $dir = storage_path('stats');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $dir.'/stats.json';
        $stats = $fresh || ! file_exists($file)
            ? []
            : (json_decode((string) file_get_contents($file), true) ?: []);

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
        file_put_contents($file, json_encode($stats, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));

        $this->info("Stats générées pour {$days} jour(s) → {$file}");

        return self::SUCCESS;
    }
}
