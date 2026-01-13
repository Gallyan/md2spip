<?php

declare(strict_types=1);

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

        // Nettoyer le rate limiter avant chaque test pour éviter les interférences
        RateLimiter::clear('markdown-convert:'.request()->ip());
    }

    /**
     * Vérifie que le composant Livewire se charge correctement
     * et affiche les éléments de base de l'interface.
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
     * Vérifie que les propriétés markdown et spip sont initialisées
     * avec des chaînes vides au chargement du composant.
     */
    public function test_markdown_property_is_initialized(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->assertSet('markdown', '')
            ->assertSet('spip', '');
    }

    /**
     * Vérifie que la conversion Markdown → SPIP se déclenche
     * automatiquement lors de la modification du texte.
     */
    public function test_converts_markdown_to_spip_on_update(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '# Titre')
            ->assertSet('spip', '{{{Titre}}}');
    }

    /**
     * Vérifie la conversion du gras Markdown (**texte**)
     * vers la syntaxe SPIP ({{texte}}).
     */
    public function test_converts_bold_text(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '**gras**')
            ->assertSet('spip', '{{gras}}');
    }

    /**
     * Vérifie la conversion de l'italique Markdown (*texte*)
     * vers la syntaxe SPIP ({texte}).
     */
    public function test_converts_italic_text(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '*italique*')
            ->assertSet('spip', '{italique}');
    }

    /**
     * Vérifie la conversion des liens Markdown [texte](url)
     * vers la syntaxe SPIP [texte->url].
     */
    public function test_converts_links(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '[lien](https://example.com)')
            ->assertSet('spip', '[lien->https://example.com]');
    }

    /**
     * Vérifie la conversion des listes à puces Markdown (- item)
     * vers la syntaxe SPIP (-* item).
     */
    public function test_converts_lists(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '- item')
            ->assertSet('spip', '-* item');
    }

    /**
     * Vérifie la conversion des citations Markdown (> texte)
     * vers la syntaxe SPIP (<quote>texte</quote>).
     */
    public function test_converts_blockquotes(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '> citation')
            ->assertSet('spip', '<quote>citation</quote>');
    }

    /**
     * Vérifie que les conversions se font en temps réel
     * et que chaque mise à jour remplace complètement la précédente.
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
     * Vérifie que les champs vides sont gérés correctement
     * sans produire d'erreur.
     */
    public function test_handles_empty_input(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', '')
            ->assertSet('spip', '');
    }

    /**
     * Vérifie que plusieurs types de formatage Markdown
     * sont convertis correctement dans un document complexe.
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
     * Vérifie que le compteur de caractères est calculé correctement
     * et s'affiche dans l'interface en temps réel.
     */
    public function test_character_count_is_computed(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', 'Hello')
            ->assertSee('5 /')
            ->set('markdown', 'Hello World!')
            ->assertSee('12 /');
    }

    /**
     * Vérifie que les textes dépassant MAX_LENGTH (100 000 caractères)
     * sont rejetés avec un message d'erreur approprié.
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
     * Vérifie que les textes à la limite exacte (100 000 caractères)
     * sont acceptés sans message d'erreur.
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
     * Vérifie que le compteur de caractères gère correctement
     * les caractères multi-octets (UTF-8) comme les accents.
     */
    public function test_character_count_handles_multibyte_characters(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', 'café')
            ->assertSee('4 /'); // é compte pour 1 caractère
    }

    /**
     * Vérifie que le rate limiter bloque les requêtes excessives
     * après avoir atteint la limite de MAX_ATTEMPTS (300) par minute.
     */
    public function test_rate_limiter_blocks_excessive_requests(): void
    {
        $component = Livewire::test(MarkdownToSpipPage::class);

        // Faire 301 requêtes pour dépasser la limite
        for ($i = 0; $i < 301; $i++) {
            $component->set('markdown', "Test $i");
        }

        $component->assertSee('Trop de requêtes');
    }

    /**
     * Vérifie la conversion du code inline Markdown (`code`)
     * vers la syntaxe SPIP (<code>code</code>).
     */
    public function test_converts_inline_code(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', 'Utiliser `echo` pour afficher')
            ->assertSet('spip', 'Utiliser <code>echo</code> pour afficher');
    }

    /**
     * Vérifie la conversion des blocs de code Markdown (```)
     * vers la syntaxe SPIP (<code>...</code>).
     */
    public function test_converts_code_blocks(): void
    {
        Livewire::test(MarkdownToSpipPage::class)
            ->set('markdown', "```\ncode\n```")
            ->assertSet('spip', "<code>\ncode\n</code>");
    }
}
