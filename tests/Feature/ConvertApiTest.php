<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Controllers\Api\ConvertController;
use App\Livewire\MarkdownToSpipPage;
use App\Support\Stats;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ConvertApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('api-convert:127.0.0.1');

        Storage::fake('stats');
    }

    public function test_converts_the_raw_body_to_spip(): void
    {
        $response = $this->postRaw("# Titre\n\nDu **gras** et un [lien](https://spip.net).");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->assertSame("{{{Titre}}}\n\nDu {{gras}} et un [lien->https://spip.net].", $response->getContent());
    }

    public function test_does_not_need_a_csrf_token_nor_a_session(): void
    {
        $response = $this->postRaw('*italique*');

        $response->assertOk();
        $response->assertHeaderMissing('Set-Cookie');
    }

    public function test_counts_conversions_apart_from_the_page_ones(): void
    {
        $this->postRaw('**gras**');
        $this->postRaw('**gras**');

        $today = Stats::read()[date('Y-m-d')];

        $this->assertSame(2, $today['api_conversions']);
        $this->assertArrayNotHasKey('conversions', $today);
        $this->assertArrayNotHasKey('sessions', $today);
    }

    public function test_an_empty_body_is_not_counted(): void
    {
        $this->postRaw('')->assertOk()->assertContent('');

        $this->assertSame([], Stats::read());
    }

    public function test_rejects_text_over_the_size_limit(): void
    {
        $response = $this->postRaw(str_repeat('a', MarkdownToSpipPage::MAX_LENGTH + 1));

        $response->assertStatus(413);
        $this->assertSame([], Stats::read());
    }

    public function test_rejects_invalid_utf8(): void
    {
        $this->postRaw("caf\xE9")->assertStatus(400);
    }

    public function test_rate_limits_by_ip(): void
    {
        for ($i = 0; $i < ConvertController::MAX_ATTEMPTS; $i++) {
            $this->postRaw('a')->assertOk();
        }

        $response = $this->postRaw('a');

        $response->assertStatus(429);
        $response->assertHeader('Retry-After');
    }

    public function test_only_accepts_post(): void
    {
        $this->get('/api/convert')->assertStatus(405);
    }

    public function test_stats_page_shows_api_conversions(): void
    {
        Stats::increment('api_conversions', 1234);

        $this->get('/stats')->assertSee("Conversions via l'API : ".Number::format(1234));
    }

    public function test_openapi_description_matches_the_endpoint(): void
    {
        $spec = json_decode((string) file_get_contents(public_path('openapi.json')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertIsArray($spec);

        AssertableJson::fromArray($spec)
            ->where('openapi', '3.1.0')
            ->where('paths./api/convert.post.requestBody.content.text/plain.schema.maxLength', MarkdownToSpipPage::MAX_LENGTH)
            ->has('paths./api/convert.post.responses', 4)
            ->has('paths./api/convert.post.responses.413')
            ->has('paths./api/convert.post.responses.429.headers.Retry-After')
            ->where('info.description', fn (string $description): bool => str_contains(
                $description,
                ConvertController::MAX_ATTEMPTS.' requests per minute',
            ))
            ->etc();
    }

    public function test_guide_documents_the_api_and_footer_links_to_it(): void
    {
        $this->get('/guide')
            ->assertSee('id="api"', false)
            ->assertSee('curl --data-binary @article.md '.url('/api/convert'), false)
            ->assertSee('href="/openapi.json"', false);

        $this->get('/')->assertSee('href="/guide#api"', false);
        $this->get('/en')->assertSee('href="/en/guide#api"', false);
    }

    /**
     * @return TestResponse<Response>
     */
    private function postRaw(string $body): TestResponse
    {
        return $this->call('POST', '/api/convert', [], [], [], ['CONTENT_TYPE' => 'text/plain'], $body);
    }
}
