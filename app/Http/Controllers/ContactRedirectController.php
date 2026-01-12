<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

/**
 * Gère la redirection email obfusquée pour protéger contre les robots spammeurs.
 *
 * Cette classe implémente la méthode d'obfuscation décrite dans :
 * https://www.orsal.fr/Obfuscation-d-email-CSS-vs
 */
final class ContactRedirectController extends Controller
{
    /**
     * Redirige vers mailto: avec l'email configuré.
     *
     * Headers anti-cache pour empêcher l'indexation et la mise en cache
     * de la redirection par les navigateurs, proxies et robots.
     *
     * @return RedirectResponse Redirection vers mailto: avec headers de sécurité
     */
    public function __invoke(): RedirectResponse
    {
        /** @var string $email */
        $email = config('app.contact_email', 'contact@example.com');

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
