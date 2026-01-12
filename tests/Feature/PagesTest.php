<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesTest extends TestCase
{
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Markdown to SPIP');
        $response->assertSee('Markdown');
        $response->assertSee('Spip');
    }

    public function test_mentions_legales_page_loads_successfully(): void
    {
        $response = $this->get('/mentions-legales');

        $response->assertStatus(200);
        $response->assertSee('Mentions légales');
        $response->assertSee('Guillaume Orsal');
        $response->assertSee('OVH');
    }

    public function test_mentions_legales_has_noindex(): void
    {
        $response = $this->get('/mentions-legales');

        $response->assertSee('noindex', false);
    }

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeaderMissing('Strict-Transport-Security'); // HSTS géré par Apache
    }

    public function test_content_security_policy_is_set(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Content-Security-Policy');
        $csp = $response->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
    }

    public function test_permissions_policy_is_set(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Permissions-Policy');
        $policy = $response->headers->get('Permissions-Policy');

        $this->assertStringContainsString('geolocation=()', $policy);
        $this->assertStringContainsString('camera=()', $policy);
    }
}
