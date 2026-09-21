<?php

declare(strict_types=1);

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Storage;

/**
 * Reads and writes the usage counters stored in stats.json.
 *
 * Counters are grouped by day: ['2026-08-20' => ['sessions' => 12, ...]].
 * Decoding rejects anything that does not fit that shape, so callers can
 * rely on the returned structure without re-checking it.
 *
 * Writers serialize on a separate lock file and replace stats.json with an
 * atomic rename, so a reader never sees a truncated file and an interrupted
 * write never destroys the stored history.
 */
final class Stats
{
    private const FILE = 'stats.json';

    private const LOCK = 'stats.lock';

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
        self::withLock(fn () => self::replace($stats));
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
     * Increment today's counter; concurrent writers wait for the lock.
     */
    public static function increment(string $key, int $value = 1): void
    {
        self::withLock(function () use ($key, $value): void {
            $stats = self::read();
            $today = date('Y-m-d');
            $stats[$today][$key] = ($stats[$today][$key] ?? 0) + $value;

            self::replace($stats);
        });
    }

    /**
     * @param  Closure(): void  $callback
     */
    private static function withLock(Closure $callback): void
    {
        self::ensureDirectoryExists();

        $handle = fopen(Storage::disk('stats')->path(self::LOCK), 'c');

        if ($handle === false) {
            return;
        }

        try {
            if (! flock($handle, LOCK_EX)) {
                return;
            }

            $callback();
        } finally {
            fclose($handle);
        }
    }

    /**
     * Write the counters to a sibling file, then rename it over stats.json.
     * Must run under the lock, which makes the temporary name unique.
     *
     * @param  array<string, array<string, int>>  $stats
     */
    private static function replace(array $stats): void
    {
        $path = self::path();
        $temporary = "{$path}.tmp";
        $json = self::encode($stats);

        if (file_put_contents($temporary, $json) !== strlen($json)) {
            @unlink($temporary);

            return;
        }

        rename($temporary, $path);
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
