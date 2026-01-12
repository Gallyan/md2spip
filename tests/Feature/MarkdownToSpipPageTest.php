<?php

namespace Tests\Feature;

use App\Livewire\MarkdownToSpipPage;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class MarkdownToSpipPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clear rate limiter before each test
        RateLimiter::clear('markdown-convert:' . request()->ip());
    }
    public function test_component_renders(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->assertStatus(200)
            ->assertSee('Markdown to SPIP')
            ->assertSee('Markdown')
            ->assertSee('Spip');
    }

    public function test_markdown_property_is_initialized(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->assertSet('markdown', '')
            ->assertSet('spip', '');
    }

    public function test_converts_markdown_to_spip_on_update(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '# Titre')
            ->assertSet('spip', '{{{Titre}}}');
    }

    public function test_converts_bold_text(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '**gras**')
            ->assertSet('spip', '{{gras}}');
    }

    public function test_converts_italic_text(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '*italique*')
            ->assertSet('spip', '{italique}');
    }

    public function test_converts_links(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '[lien](https://example.com)')
            ->assertSet('spip', '[lien->https://example.com]');
    }

    public function test_converts_lists(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '- item')
            ->assertSet('spip', '-* item');
    }

    public function test_converts_blockquotes(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '> citation')
            ->assertSet('spip', '<quote>citation</quote>');
    }

    public function test_updates_in_real_time(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '# Premier')
            ->assertSet('spip', '{{{Premier}}}')
            ->set('markdown', '## Deuxième')
            ->assertSet('spip', '{{Deuxième}}');
    }

    public function test_handles_empty_input(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '')
            ->assertSet('spip', '');
    }

    public function test_handles_complex_markdown(): void
    {
        $markdown = "# Titre\n\n**Gras** et *italique*\n\n- Liste\n\n[Lien](https://spip.net)";
        $expectedSpip = "{{{Titre}}}\n\n{{Gras}} et {italique}\n\n-* Liste\n\n[Lien->https://spip.net]";

        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', $markdown)
            ->assertSet('spip', $expectedSpip);
    }

    public function test_character_count_is_computed(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', 'Hello')
            ->assertSee('5 /')
            ->set('markdown', 'Hello World!')
            ->assertSee('12 /');
    }

    public function test_rejects_text_exceeding_max_length(): void
    {
        $maxLength = MarkdownToSpipPage::MAX_LENGTH;
        $tooLongText = str_repeat('a', $maxLength + 1);

        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', $tooLongText)
            ->assertSee('Texte trop long');
    }

    public function test_accepts_text_at_max_length(): void
    {
        $maxLength = MarkdownToSpipPage::MAX_LENGTH;
        $exactText = str_repeat('a', $maxLength);

        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', $exactText)
            ->assertDontSee('Texte trop long');
    }

    public function test_character_count_handles_multibyte_characters(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', 'café')
            ->assertSee('4 /'); // é compte pour 1 caractère
    }

    public function test_request_counter_increments(): void
    {
        $component = Livewire::test(MarkdownToSpipPage::class);

        // Première requête
        $component->set('markdown', 'Test 1');
        $this->assertGreaterThan(0, $component->get('requestCount'));

        // Deuxième requête
        $component->set('markdown', 'Test 2');
        $this->assertGreaterThan(1, $component->get('requestCount'));
    }

    public function test_rate_limiter_blocks_excessive_requests(): void
    {
        $component = Livewire::test(MarkdownToSpipPage::class);

        // Faire 301 requêtes pour dépasser la limite
        for ($i = 0; $i < 301; $i++) {
            $component->set('markdown', "Test $i");
        }

        $component->assertSee('Trop de requêtes');
    }

    public function test_converts_inline_code(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', 'Utiliser `echo` pour afficher')
            ->assertSet('spip', 'Utiliser <code>echo</code> pour afficher');
    }

    public function test_converts_code_blocks(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', "```\ncode\n```")
            ->assertSet('spip', "<code>\ncode\n</code>");
    }
}
