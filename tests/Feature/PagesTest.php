<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PagesTest extends TestCase
{
    /**
     * Vérifie que la page d'accueil se charge correctement
     * et affiche les éléments principaux de l'interface.
     */
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Markdown to SPIP');
        $response->assertSee('Markdown');
        $response->assertSee('Spip');
    }

    /**
     * Vérifie que la page mentions légales se charge correctement
     * et affiche les informations de l'éditeur et de l'hébergeur.
     */
    public function test_mentions_legales_page_loads_successfully(): void
    {
        $response = $this->get('/mentions-legales');

        $response->assertStatus(200);
        $response->assertSee('Mentions légales');
        $response->assertSee('Éditeur du site');
        $response->assertSee('Hébergement');
    }

    /**
     * Vérifie que la page mentions légales contient la balise meta noindex
     * pour empêcher l'indexation par les moteurs de recherche.
     */
    public function test_mentions_legales_has_noindex(): void
    {
        $response = $this->get('/mentions-legales');

        $response->assertSee('noindex', false);
    }

    /**
     * Vérifie que les headers de sécurité principaux sont présents
     * (X-Frame-Options, X-Content-Type-Options, Referrer-Policy).
     * Note: HSTS est géré par Apache en production.
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeaderMissing('Strict-Transport-Security'); // HSTS géré par Apache
    }

    /**
     * Vérifie que le Content-Security-Policy est configuré
     * avec des directives de base (default-src et frame-ancestors).
     */
    public function test_content_security_policy_is_set(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Content-Security-Policy');
        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertNotNull($csp);

        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
    }

    /**
     * Vérifie que le Permissions-Policy désactive les APIs sensibles
     * (geolocation, camera, microphone, etc.).
     */
    public function test_permissions_policy_is_set(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Permissions-Policy');
        $policy = $response->headers->get('Permissions-Policy');
        $this->assertNotNull($policy);

        $this->assertStringContainsString('geolocation=()', $policy);
        $this->assertStringContainsString('camera=()', $policy);
    }

    /**
     * Vérifie que la route /contact-email redirige correctement
     * vers mailto: avec l'email configuré dans .env
     */
    public function test_contact_email_redirects_to_mailto(): void
    {
        config(['legal.contact_email' => 'test@example.com']);

        $response = $this->get('/contact-email');

        $response->assertRedirect('mailto:test@example.com?subject=Contact');
    }

    /**
     * Vérifie que la route /contact-email contient tous les headers anti-cache
     * pour empêcher l'indexation et la mise en cache par les navigateurs/robots.
     */
    public function test_contact_email_has_anti_cache_headers(): void
    {
        $response = $this->get('/contact-email');

        $response->assertHeader('Cache-Control');
        $cacheControl = $response->headers->get('Cache-Control');
        $this->assertNotNull($cacheControl);
        $this->assertStringContainsString('no-cache', $cacheControl);
        $this->assertStringContainsString('no-store', $cacheControl);
        $this->assertStringContainsString('must-revalidate', $cacheControl);

        $response->assertHeader('Pragma', 'no-cache');
        $response->assertHeader('Expires', '0');
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }
}
