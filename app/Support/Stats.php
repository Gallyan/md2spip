<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Reads and writes the usage counters stored in stats.json.
 *
 * Counters are grouped by day: ['2026-08-20' => ['sessions' => 12, ...]].
 * Decoding rejects anything that does not fit that shape, so callers can
 * rely on the returned structure without re-checking it.
 */
final class Stats
{
    private const FILE = 'stats.json';

    /**
     * @return array<string, array<string, int>>
     */
    public static function read(): array
    {
        $disk = Storage::disk('stats');

        if (! $disk->exists(self::FILE)) {
            return [];
        }

        return self::decode((string) $disk->get(self::FILE));
    }

    /**
     * @param  array<string, array<string, int>>  $stats
     */
    public static function write(array $stats): void
    {
        self::ensureDirectoryExists();

        Storage::disk('stats')->put(self::FILE, self::encode($stats));
    }

    public static function exists(): bool
    {
        return Storage::disk('stats')->exists(self::FILE);
    }

    public static function path(): string
    {
        return Storage::disk('stats')->path(self::FILE);
    }

    /**
     * Increment today's counter under an exclusive lock to prevent concurrent writes.
     */
    public static function increment(string $key, int $value = 1): void
    {
        self::ensureDirectoryExists();

        $handle = fopen(self::path(), 'c+');

        if ($handle === false) {
            return;
        }

        try {
            if (! flock($handle, LOCK_EX)) {
                return;
            }

            $content = stream_get_contents($handle);
            $stats = $content === false ? [] : self::decode($content);
            $today = date('Y-m-d');
            $stats[$today][$key] = ($stats[$today][$key] ?? 0) + $value;

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, self::encode($stats));
            fflush($handle);
            flock($handle, LOCK_UN);
        } finally {
            fclose($handle);
        }
    }

    /**
     * @return array<string, array<string, int>>
     */
    private static function decode(string $json): array
    {
        $decoded = json_decode($json, true);

        if (! is_array($decoded)) {
            return [];
        }

        $stats = [];

        foreach ($decoded as $date => $counters) {
            if (! is_array($counters)) {
                continue;
            }

            foreach ($counters as $key => $value) {
                if (is_int($value)) {
                    $stats[(string) $date][(string) $key] = $value;
                }
            }
        }

        return $stats;
    }

    /**
     * @param  array<string, array<string, int>>  $stats
     */
    private static function encode(array $stats): string
    {
        return (string) json_encode($stats, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    private static function ensureDirectoryExists(): void
    {
        $root = Storage::disk('stats')->path('');

        if (! is_dir($root)) {
            mkdir($root, 0755, true);
        }
    }
}
