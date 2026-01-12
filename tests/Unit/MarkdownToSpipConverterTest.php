<?php

namespace Tests\Unit;

use App\Support\MarkdownToSpipConverter;
use PHPUnit\Framework\TestCase;

class MarkdownToSpipConverterTest extends TestCase
{
    public function test_converts_h1_title(): void
    {
        $markdown = '# Mon titre';
        $expected = '{{{Mon titre}}}';
        
        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_h2_title(): void
    {
        $markdown = '## Sous-titre';
        $expected = '{{Sous-titre}}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_multiple_title_levels(): void
    {
        $markdown = "# Titre 1\n## Titre 2\n### Titre 3";
        $expected = "{{{Titre 1}}}\n{{Titre 2}}\n{{Titre 3}}";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_bold_text(): void
    {
        $markdown = '**texte en gras**';
        $expected = '{{texte en gras}}';
        
        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_italic_text(): void
    {
        $markdown = '*texte en italique*';
        $expected = '{texte en italique}';
        
        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_links(): void
    {
        $markdown = '[SPIP](https://www.spip.net)';
        $expected = '[SPIP->https://www.spip.net]';
        
        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_lists(): void
    {
        $markdown = "- Premier item\n- Deuxième item";
        $expected = "-* Premier item\n-* Deuxième item";
        
        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_blockquotes(): void
    {
        $markdown = '> Citation importante';
        $expected = '<quote>Citation importante</quote>';
        
        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_complex_markdown(): void
    {
        $markdown = "# Article\n\n**Introduction** en *gras* et italique.\n\n- Liste 1\n- Liste 2\n\n[Lien](https://example.com)\n\n> Citation";
        $expected = "{{{Article}}}\n\n{{Introduction}} en {gras} et italique.\n\n-* Liste 1\n-* Liste 2\n\n[Lien->https://example.com]\n\n<quote>Citation</quote>";
        
        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_handles_empty_string(): void
    {
        $this->assertEquals('', MarkdownToSpipConverter::convert(''));
    }

    public function test_handles_text_without_markdown(): void
    {
        $text = 'Texte simple sans formatage';
        $this->assertEquals($text, MarkdownToSpipConverter::convert($text));
    }

    public function test_converts_inline_code(): void
    {
        $markdown = 'Utiliser `echo` pour afficher';
        $expected = 'Utiliser <code>echo</code> pour afficher';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_code_blocks(): void
    {
        $markdown = "Exemple de code:\n\n```\nfunction hello() {\n  return 'world';\n}\n```";
        $expected = "Exemple de code:\n\n<code>\nfunction hello() {\n  return 'world';\n}\n</code>";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_code_blocks_protect_content_from_conversion(): void
    {
        $markdown = '```**gras** et *italique*```';
        $expected = '<code>**gras** et *italique*</code>';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_footnotes(): void
    {
        $markdown = "Texte avec note[^1] et autre note[^2].\n\n[^1]: Première note\n[^2]: Deuxième note";
        $expected = "Texte avec note[[Première note]] et autre note[[Deuxième note]].\n\n\n";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_converts_single_footnote(): void
    {
        $markdown = "Un texte[^ref].\n\n[^ref]: Contenu de la note";
        $expected = "Un texte[[Contenu de la note]].\n\n";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_footnote_without_definition_stays_unchanged(): void
    {
        $markdown = "Texte avec référence[^1] sans définition.";
        $expected = "Texte avec référence[^1] sans définition.";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    public function test_complex_document_with_footnotes(): void
    {
        $markdown = "# Titre\n\nParagraphe avec note[^1].\n\n## Sous-titre\n\n**Gras** avec note[^2].\n\n[^1]: Note importante\n[^2]: Autre note";
        $expected = "{{{Titre}}}\n\nParagraphe avec note[[Note importante]].\n\n{{Sous-titre}}\n\n{{Gras}} avec note[[Autre note]].\n\n\n";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }
}
