<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Resolves locale-aware URLs from the current named route.
 *
 * Routes are duplicated per locale: the French set keeps bare names
 * (home, legal, stats, contact) and the English set shares those names
 * under the "en." prefix. Stripping that prefix yields a base name that
 * rebuilds either side without parsing the request path.
 */
final class LocaleUrls
{
    public static function locale(): string
    {
        return app()->getLocale();
    }

    public static function isEnglish(): bool
    {
        return self::locale() === 'en';
    }

    public static function alternateFr(): string
    {
        return route(self::baseRouteName());
    }

    public static function alternateEn(): string
    {
        return route('en.'.self::baseRouteName());
    }

    public static function current(): string
    {
        return self::isEnglish() ? self::alternateEn() : self::alternateFr();
    }

    /**
     * Page routes that own a French/English pair. A Livewire update request
     * runs under the internal "livewire.update" route, so anything outside
     * this set falls back to "home" — the only full-page Livewire component.
     */
    private const PAGE_ROUTES = ['home', 'legal', 'stats', 'contact'];

    private static function baseRouteName(): string
    {
        $name = Route::currentRouteName() ?? 'home';
        $name = str_starts_with($name, 'en.') ? substr($name, 3) : $name;

        return in_array($name, self::PAGE_ROUTES, true) ? $name : 'home';
    }
}
