<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\MarkdownToSpipPage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MarkdownToSpipPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clear the rate limiter before each test to avoid interference
        RateLimiter::clear('markdown-convert:'.request()->ip());

        // Isolate stats writes so tests don't pollute the real file
        Storage::fake('stats');
    }

    /**
     * Verifies that the Livewire component loads and shows the basic UI.
     */
    public function test_component_renders(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->assertStatus(200)
            ->assertSee('Markdown to SPIP')
            ->assertSee('Markdown')
            ->assertSee('Spip');
    }

    /**
     * Verifies that markdown and spip properties are initialized to empty strings.
     */
    public function test_markdown_property_is_initialized(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->assertSet('markdown', '')
            ->assertSet('spip', '');
    }

    /**
     * Verifies that Markdown → SPIP conversion is triggered automatically on text change.
     */
    public function test_converts_markdown_to_spip_on_update(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '# Titre')
            ->assertSet('spip', '{{{Titre}}}');
    }

    /**
     * Verifies bold conversion (**text** → {{text}}).
     */
    public function test_converts_bold_text(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '**gras**')
            ->assertSet('spip', '{{gras}}');
    }

    /**
     * Verifies italic conversion (*text* → {text}).
     */
    public function test_converts_italic_text(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '*italique*')
            ->assertSet('spip', '{italique}');
    }

    /**
     * Verifies link conversion ([text](url) → [text->url]).
     */
    public function test_converts_links(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '[lien](https://example.com)')
            ->assertSet('spip', '[lien->https://example.com]');
    }

    /**
     * Verifies bullet list conversion (- item → -* item).
     */
    public function test_converts_lists(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '- item')
            ->assertSet('spip', '-* item');
    }

    /**
     * Verifies blockquote conversion (> text → <quote>text</quote>).
     */
    public function test_converts_blockquotes(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '> citation')
            ->assertSet('spip', '<quote>citation</quote>');
    }

    /**
     * Verifies that conversions happen in real time and each update fully replaces the previous one.
     */
    public function test_updates_in_real_time(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '# Premier')
            ->assertSet('spip', '{{{Premier}}}')
            ->set('markdown', '## Deuxième')
            ->assertSet('spip', '{{Deuxième}}');
    }

    /**
     * Verifies that empty input is handled correctly without error.
     */
    public function test_handles_empty_input(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '')
            ->assertSet('spip', '');
    }

    /**
     * Verifies that multiple Markdown formats are converted properly in a complex document.
     */
    public function test_handles_complex_markdown(): void
    {
        $markdown = "# Titre\n\n**Gras** et *italique*\n\n- Liste\n\n[Lien](https://spip.net)";
        $expectedSpip = "{{{Titre}}}\n\n{{Gras}} et {italique}\n\n-* Liste\n\n[Lien->https://spip.net]";

        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', $markdown)
            ->assertSet('spip', $expectedSpip);
    }

    /**
     * Verifies that text exceeding MAX_LENGTH (100,000 chars) is rejected with an error message.
     */
    public function test_rejects_text_exceeding_max_length(): void
    {
        $maxLength = MarkdownToSpipPage::MAX_LENGTH;
        $tooLongText = str_repeat('a', $maxLength + 1);

        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', $tooLongText)
            ->assertSee('Texte trop long');
    }

    /**
     * Verifies that text exactly at the max length is accepted without an error.
     */
    public function test_accepts_text_at_max_length(): void
    {
        $maxLength = MarkdownToSpipPage::MAX_LENGTH;
        $exactText = str_repeat('a', $maxLength);

        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', $exactText)
            ->assertDontSee('Texte trop long');
    }

    /**
     * Verifies that the rate limiter blocks excessive requests after MAX_ATTEMPTS per minute.
     */
    public function test_rate_limiter_blocks_excessive_requests(): void
    {
        $component = Livewire::test(MarkdownToSpipPage::class);

        // Send 301 requests to exceed the limit
        for ($i = 0; $i < 301; $i++) {
            $component->set('markdown', "Test $i");
        }

        $component->assertSee('Trop de requêtes');
    }

    /**
     * Verifies inline code conversion (`code` → <code>code</code>).
     */
    public function test_converts_inline_code(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', 'Utiliser `echo` pour afficher')
            ->assertSet('spip', 'Utiliser <code>echo</code> pour afficher');
    }

    /**
     * Verifies code block conversion (``` → <code>...</code>).
     */
    public function test_converts_code_blocks(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', "```\ncode\n```")
            ->assertSet('spip', "<code>\ncode\n</code>");
    }

    /**
     * Verifies that trackConversion increments the "conversions" stat.
     * The real trigger happens client-side via localStorage (Alpine).
     */
    public function test_track_conversion_increments_conversions(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->call('trackConversion');

        $stats = json_decode((string) Storage::disk('stats')->get('stats.json'), true);
        $today = date('Y-m-d');

        $this->assertSame(1, $stats[$today]['conversions']);
    }

    /**
     * Verifies that countCopy increments copies and total_chars.
     */
    public function test_count_copy_increments_copies_and_chars(): void
    {
        $component = Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', 'bonjour');

        $component->call('countCopy');
        $component->call('countCopy');

        $stats = json_decode((string) Storage::disk('stats')->get('stats.json'), true);
        $today = date('Y-m-d');

        $this->assertSame(2, $stats[$today]['copies']);
        $this->assertSame(mb_strlen('bonjour') * 2, $stats[$today]['total_chars']);
    }
}
