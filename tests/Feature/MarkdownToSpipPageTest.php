<?php

namespace Tests\Feature;

use App\Livewire\MarkdownToSpipPage;
use Livewire\Livewire;
use Tests\TestCase;

class MarkdownToSpipPageTest extends TestCase
{
    public function test_component_renders(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->assertStatus(200)
            ->assertSee('Markdown to SPIP')
            ->assertSee('MARKDOWN')
            ->assertSee('SPIP');
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
            ->assertSet('spip', '{{{Deuxième}}}');
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
}
