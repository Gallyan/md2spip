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
        $response->assertSee('MARKDOWN');
        $response->assertSee('SPIP');
    }

    public function test_mentions_legales_page_loads_successfully(): void
    {
        $response = $this->get('/mentions-legales');

        $response->assertStatus(200);
        $response->assertSee('Mentions légales');
        $response->assertSee('Guillaume Orsal');
        $response->assertSee('OVH SAS');
    }

    public function test_mentions_legales_has_noindex(): void
    {
        $response = $this->get('/mentions-legales');

        $response->assertSee('noindex', false);
    }
}
