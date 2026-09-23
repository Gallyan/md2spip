<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Http\Request;
use Livewire\Mechanisms\HandleRequests\EndpointResolver;
use Tests\TestCase;

class PagesTest extends TestCase
{
    protected function tearDown(): void
    {
        // Trusted hosts live in a static on the Symfony request class
        Request::setTrustedHosts([]);

        parent::tearDown();
    }

    /**
     * Verifies that the home page loads successfully
     * and displays the main interface elements.
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
     * Verifies that the legal notice page loads successfully
     * and displays editor and hosting information.
     */
    public function test_mentions_legales_page_loads_successfully(): void
    {
        config([
            'legal.editor_name' => 'Acme',
            'legal.hosting.name' => 'OVH',
        ]);

        $response = $this->get('/mentions-legales');

        $response->assertStatus(200);
        $response->assertSee('Mentions légales');
        $response->assertSee('Éditeur du site');
        $response->assertSee('Hébergement');
        $response->assertSee('Acme');
        $response->assertSee('OVH');
    }

    public function test_mentions_legales_hides_hosting_section_when_not_configured(): void
    {
        config(['legal.hosting.name' => null]);

        $response = $this->get('/mentions-legales');

        $response->assertStatus(200);
        $response->assertDontSee('Hébergement');
    }

    /**
     * Verifies that the legal notice page contains the noindex meta tag
     * to prevent indexing by search engines.
     */
    public function test_mentions_legales_has_noindex(): void
    {
        $response = $this->get('/mentions-legales');

        $response->assertSee('noindex', false);
    }

    /**
     * Verifies that the main security headers are present
     * (X-Frame-Options, X-Content-Type-Options, Referrer-Policy).
     * Note: HSTS is handled by Apache in production.
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeaderMissing('Strict-Transport-Security');
    }

    /**
     * Verifies that Content-Security-Policy is set
     * with baseline directives (default-src and frame-ancestors).
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
     * Verifies that Permissions-Policy disables sensitive APIs
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
     * Verifies that the /contact route redirects to mailto:
     * with the email configured in .env.
     */
    public function test_contact_redirects_to_mailto(): void
    {
        config(['legal.contact_email' => 'test@example.com']);

        $response = $this->get('/contact');

        $response->assertRedirect('mailto:test@example.com?subject=Contact');
    }

    /**
     * Verifies that the /contact route returns anti-cache headers
     * to prevent indexing and caching by browsers and bots.
     */
    public function test_contact_has_anti_cache_headers(): void
    {
        $response = $this->get('/contact');

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

    public function test_sitemap_xml_serves_xml_with_home_url(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<urlset', false);
        $response->assertSee(url('/'), false);
        $response->assertSee('<lastmod>', false);
    }

    public function test_robots_txt_lists_disallows_and_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('User-agent: *', false);
        $response->assertSee('Disallow: /mentions-legales', false);
        $response->assertSee('Disallow: /stats', false);
        $response->assertSee('Disallow: /contact', false);
        $response->assertSee('Sitemap: '.url('/sitemap.xml'), false);
    }

    public function test_english_home_loads(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
        $response->assertSee('<html lang="en"', false);
        $response->assertSee('Free, instant online converter');
    }

    public function test_english_legal_loads(): void
    {
        config([
            'legal.editor_name' => 'Acme',
            'legal.hosting.name' => 'OVH',
        ]);

        $response = $this->get('/en/legal');

        $response->assertStatus(200);
        $response->assertSee('Legal notice');
        $response->assertSee('Site editor');
        $response->assertSee('Hosting');
    }

    public function test_english_stats_loads(): void
    {
        $response = $this->get('/en/stats');

        $response->assertStatus(200);
        $response->assertSee('Usage statistics');
        $response->assertSee('Last 30 days');
    }

    public function test_english_contact_redirects(): void
    {
        config(['legal.contact_email' => 'test@example.com']);

        $response = $this->get('/en/contact');

        $response->assertRedirect('mailto:test@example.com?subject=Contact');
    }

    public function test_home_has_hreflang_alternates(): void
    {
        $response = $this->get('/');

        $response->assertSee('hreflang="fr"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="x-default"', false);
    }

    public function test_sitemap_includes_both_locales(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertSee(url('/').'</loc>', false);
        $response->assertSee(url('/en').'</loc>', false);
        $response->assertSee('hreflang="fr"', false);
        $response->assertSee('hreflang="en"', false);
    }

    /**
     * Verifies that outside local and tests, a Host other than APP_URL's is refused
     * instead of being echoed into the published absolute URLs.
     */
    public function test_foreign_host_is_refused_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config(['app.url' => 'https://md2spip.test']);

        $this->get('https://evil.example.net/sitemap.xml')->assertStatus(400);

        $response = $this->get('https://md2spip.test/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee('<loc>https://md2spip.test</loc>', false);
    }

    /**
     * Verifies that the JSON-LD block stays valid JSON and cannot be closed early,
     * even when a configured value carries a closing script tag.
     */
    public function test_structured_data_is_valid_json(): void
    {
        config(['legal.social.github' => 'https://example.test/</script><script>alert(1)</script>']);

        $response = $this->get('/');

        $response->assertDontSee('</script><script>alert(1)', false);

        $matched = preg_match('#<script type="application/ld\+json">(.*?)</script>#s', (string) $response->getContent(), $matches);
        $this->assertSame(1, $matched);

        $graph = json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR);
        $this->assertIsArray($graph);
        $this->assertArrayHasKey('@graph', $graph);
    }

    /**
     * Verifies that the social website link is only rendered for an http(s) URL.
     */
    public function test_legal_page_drops_a_non_http_website_link(): void
    {
        config(['legal.social.website' => 'javascript:alert(1)']);

        $response = $this->get('/mentions-legales');

        $response->assertStatus(200);
        $response->assertDontSee('javascript:alert(1)', false);
    }

    /**
     * Verifies that a request for a Livewire JS or CSS module, which no component
     * ships, is a 404 and does not write an error to the log.
     */
    public function test_livewire_module_requests_are_not_found_and_not_reported(): void
    {
        $log = storage_path('logs/livewire-modules-test.log');
        @unlink($log);
        config(['logging.default' => 'single', 'logging.channels.single.path' => $log]);

        $prefix = EndpointResolver::prefix();

        $this->get("{$prefix}/js/markdown-to-spip-page.js")->assertNotFound();
        $this->get("{$prefix}/css/does-not-exist.css")->assertNotFound();
        $this->get("{$prefix}/css/markdown-to-spip-page.global.css")->assertNotFound();

        $this->assertFileDoesNotExist($log);
    }
}
