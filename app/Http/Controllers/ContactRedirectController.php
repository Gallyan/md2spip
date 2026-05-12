<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

/**
 * Handles the obfuscated email redirect to protect against spam bots.
 *
 * This class implements the obfuscation method described at:
 * https://www.orsal.fr/Obfuscation-d-email-CSS-vs
 */
final class ContactRedirectController extends Controller
{
    /**
     * Redirect to mailto: with the configured email address.
     *
     * Anti-cache headers prevent indexing and caching of the redirect
     * by browsers, proxies and bots.
     *
     * @return RedirectResponse Redirect to mailto: with security headers
     */
    public function __invoke(): RedirectResponse
    {
        /** @var string $email */
        $email = config('legal.contact_email', 'contact@example.com');

        return redirect()
            ->away("mailto:{$email}?subject=Contact")
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Robots-Tag' => 'noindex, nofollow',
            ]);
    }
}
