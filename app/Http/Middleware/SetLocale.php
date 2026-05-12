<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sets the application locale from the route prefix.
 * Default is the configured app fallback (French here);
 * routes under "/en" set the locale to "en".
 */
final class SetLocale
{
    public function handle(Request $request, Closure $next, ?string $locale = null): Response
    {
        if ($locale !== null && in_array($locale, ['fr', 'en'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
