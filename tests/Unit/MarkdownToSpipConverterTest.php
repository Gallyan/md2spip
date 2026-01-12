<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\MarkdownToSpipConverter;
use PHPUnit\Framework\TestCase;

class MarkdownToSpipConverterTest extends TestCase
{
    /**
     * Vérifie la conversion des titres H1 Markdown (# texte)
     * vers la syntaxe SPIP ({{{texte}}}).
     */
    public function test_converts_h1_title(): void
    {
        $markdown = '# Mon titre';
        $expected = '{{{Mon titre}}}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion des titres H2 Markdown (## texte)
     * vers la syntaxe SPIP ({{texte}}).
     */
    public function test_converts_h2_title(): void
    {
        $markdown = '## Sous-titre';
        $expected = '{{Sous-titre}}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie que tous les niveaux de titres (H1 à H6)
     * sont convertis correctement (H1 → {{{}}}, H2-H6 → {{}}).
     */
    public function test_converts_multiple_title_levels(): void
    {
        $markdown = "# Titre 1\n## Titre 2\n### Titre 3";
        $expected = "{{{Titre 1}}}\n{{Titre 2}}\n{{Titre 3}}";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion du gras Markdown (**texte**)
     * vers la syntaxe SPIP ({{texte}}).
     */
    public function test_converts_bold_text(): void
    {
        $markdown = '**texte en gras**';
        $expected = '{{texte en gras}}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion de l'italique Markdown (*texte*)
     * vers la syntaxe SPIP ({texte}).
     */
    public function test_converts_italic_text(): void
    {
        $markdown = '*texte en italique*';
        $expected = '{texte en italique}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion des liens Markdown [texte](url)
     * vers la syntaxe SPIP [texte->url].
     */
    public function test_converts_links(): void
    {
        $markdown = '[SPIP](https://www.spip.net)';
        $expected = '[SPIP->https://www.spip.net]';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion des listes à puces Markdown (- item)
     * vers la syntaxe SPIP (-* item).
     */
    public function test_converts_lists(): void
    {
        $markdown = "- Premier item\n- Deuxième item";
        $expected = "-* Premier item\n-* Deuxième item";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion des citations Markdown (> texte)
     * vers la syntaxe SPIP (<quote>texte</quote>).
     */
    public function test_converts_blockquotes(): void
    {
        $markdown = '> Citation importante';
        $expected = '<quote>Citation importante</quote>';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie que tous les types de formatage sont convertis correctement
     * dans un document Markdown complexe combinant plusieurs syntaxes.
     */
    public function test_converts_complex_markdown(): void
    {
        $markdown = "# Article\n\n**Introduction** en *gras* et italique.\n\n- Liste 1\n- Liste 2\n\n[Lien](https://example.com)\n\n> Citation";
        $expected = "{{{Article}}}\n\n{{Introduction}} en {gras} et italique.\n\n-* Liste 1\n-* Liste 2\n\n[Lien->https://example.com]\n\n<quote>Citation</quote>";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie que les chaînes vides sont gérées correctement
     * et retournent une chaîne vide sans erreur.
     */
    public function test_handles_empty_string(): void
    {
        $this->assertEquals('', MarkdownToSpipConverter::convert(''));
    }

    /**
     * Vérifie que le texte sans formatage Markdown
     * est retourné inchangé.
     */
    public function test_handles_text_without_markdown(): void
    {
        $text = 'Texte simple sans formatage';
        $this->assertEquals($text, MarkdownToSpipConverter::convert($text));
    }

    /**
     * Vérifie la conversion du code inline Markdown (`code`)
     * vers la syntaxe SPIP (<code>code</code>).
     */
    public function test_converts_inline_code(): void
    {
        $markdown = 'Utiliser `echo` pour afficher';
        $expected = 'Utiliser <code>echo</code> pour afficher';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion des blocs de code Markdown (```)
     * vers la syntaxe SPIP (<code>...</code>).
     */
    public function test_converts_code_blocks(): void
    {
        $markdown = "Exemple de code:\n\n```\nfunction hello() {\n  return 'world';\n}\n```";
        $expected = "Exemple de code:\n\n<code>\nfunction hello() {\n  return 'world';\n}\n</code>";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie que le contenu des blocs de code est protégé
     * et que les syntaxes Markdown à l'intérieur ne sont pas converties.
     */
    public function test_code_blocks_protect_content_from_conversion(): void
    {
        $markdown = '```**gras** et *italique*```';
        $expected = '<code>**gras** et *italique*</code>';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion des notes de bas de page Markdown ([^ref])
     * vers la syntaxe SPIP ([[note]]) avec suppression des définitions.
     */
    public function test_converts_footnotes(): void
    {
        $markdown = "Texte avec note[^1] et autre note[^2].\n\n[^1]: Première note\n[^2]: Deuxième note";
        $expected = "Texte avec note[[Première note]] et autre note[[Deuxième note]].\n\n\n";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion d'une note de bas de page unique
     * avec référence et définition.
     */
    public function test_converts_single_footnote(): void
    {
        $markdown = "Un texte[^ref].\n\n[^ref]: Contenu de la note";
        $expected = "Un texte[[Contenu de la note]].\n\n";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie que les références de notes sans définition
     * sont laissées intactes dans le texte converti.
     */
    public function test_footnote_without_definition_stays_unchanged(): void
    {
        $markdown = 'Texte avec référence[^1] sans définition.';
        $expected = 'Texte avec référence[^1] sans définition.';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie que les notes de bas de page sont converties correctement
     * dans un document complexe combinant titres, formatage et notes.
     */
    public function test_complex_document_with_footnotes(): void
    {
        $markdown = "# Titre\n\nParagraphe avec note[^1].\n\n## Sous-titre\n\n**Gras** avec note[^2].\n\n[^1]: Note importante\n[^2]: Autre note";
        $expected = "{{{Titre}}}\n\nParagraphe avec note[[Note importante]].\n\n{{Sous-titre}}\n\n{{Gras}} avec note[[Autre note]].\n\n\n";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie que les blocs de code avec nom de langage (```js, ```php)
     * suppriment correctement le nom du langage lors de la conversion.
     */
    public function test_code_blocks_ignore_language_name(): void
    {
        $markdown = "```js\nconst x = 42;\n```";
        $expected = "<code>\nconst x = 42;\n</code>";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion de l'italique avec underscore (_texte_)
     * vers la syntaxe SPIP ({texte}).
     */
    public function test_converts_italic_with_underscore(): void
    {
        $markdown = '_texte en italique_';
        $expected = '{texte en italique}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion du gras avec double underscore (__texte__)
     * vers la syntaxe SPIP ({{texte}}).
     */
    public function test_converts_bold_with_underscore(): void
    {
        $markdown = '__texte en gras__';
        $expected = '{{texte en gras}}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion du gras+italique combiné (***texte***)
     * vers la syntaxe SPIP ({{ { texte } }}).
     */
    public function test_converts_bold_and_italic_combined_with_asterisks(): void
    {
        $markdown = '***texte gras et italique***';
        $expected = '{{ { texte gras et italique } }}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion du gras+italique combiné (___texte___)
     * vers la syntaxe SPIP ({{ { texte } }}).
     */
    public function test_converts_bold_and_italic_combined_with_underscores(): void
    {
        $markdown = '___texte gras et italique___';
        $expected = '{{ { texte gras et italique } }}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Vérifie la conversion du texte barré (~~texte~~)
     * vers la syntaxe SPIP (<del>texte</del>).
     */
    public function test_converts_strikethrough(): void
    {
        $markdown = 'Texte avec ~~barré~~ dedans';
        $expected = 'Texte avec <del>barré</del> dedans';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }
}
