<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Empêcher l'affichage dans une iframe (clickjacking protection)
        // Ne pas écraser si déjà défini par Apache
        if (! $response->headers->has('X-Frame-Options')) {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        }

        // Empêcher le navigateur de deviner le type MIME
        // Ne pas écraser si déjà défini par Apache
        if (! $response->headers->has('X-Content-Type-Options')) {
            $response->headers->set('X-Content-Type-Options', 'nosniff');
        }

        // Content Security Policy - Permet Alpine.js avec attributs inline
        // On écrase toujours la CSP d'Apache car notre app nécessite Alpine.js
        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'", // Alpine.js nécessite inline + eval pour x-data, @click, etc.
            "style-src 'self' 'unsafe-inline'", // TailwindCSS + styles inline occasionnels
            "img-src 'self' data:",
            "font-src 'self' data:",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ]), true); // true = replace existing header

        // Referrer Policy - Ne pas envoyer de referrer vers des sites externes
        // Ne pas écraser si déjà défini par Apache
        if (! $response->headers->has('Referrer-Policy')) {
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        }

        // Permissions Policy - Désactiver les APIs sensibles
        // Ne pas écraser si déjà défini par Apache (ex: config globale fullscreen=*)
        if (! $response->headers->has('Permissions-Policy')) {
            $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), fullscreen=*');
        }

        // Note: Strict-Transport-Security (HSTS) est géré par Apache car il nécessite HTTPS

        return $response;
    }
}
