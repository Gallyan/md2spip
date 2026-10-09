<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\MarkdownToSpipConverter;
use PHPUnit\Framework\TestCase;

class MarkdownToSpipConverterTest extends TestCase
{
    /**
     * Verifies H1 conversion (# text → {{{text}}}).
     */
    public function test_converts_h1_title(): void
    {
        $markdown = '# Mon titre';
        $expected = '{{{Mon titre}}}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies H2 conversion (## text → {{text}}).
     */
    public function test_converts_h2_title(): void
    {
        $markdown = '## Sous-titre';
        $expected = '{{Sous-titre}}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that all heading levels (H1 to H6) are converted properly
     * (H1 → {{{}}}, H2-H6 → {{}}).
     */
    public function test_converts_multiple_title_levels(): void
    {
        $markdown = "# Titre 1\n## Titre 2\n### Titre 3";
        $expected = "{{{Titre 1}}}\n{{Titre 2}}\n{{Titre 3}}";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies bold conversion (**text** → {{text}}).
     */
    public function test_converts_bold_text(): void
    {
        $markdown = '**texte en gras**';
        $expected = '{{texte en gras}}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies italic conversion (*text* → {text}).
     */
    public function test_converts_italic_text(): void
    {
        $markdown = '*texte en italique*';
        $expected = '{texte en italique}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies link conversion ([text](url) → [text->url]).
     */
    public function test_converts_links(): void
    {
        $markdown = '[SPIP](https://www.spip.net)';
        $expected = '[SPIP->https://www.spip.net]';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies bullet list conversion (- item → -* item).
     */
    public function test_converts_lists(): void
    {
        $markdown = "- Premier item\n- Deuxième item";
        $expected = "-* Premier item\n-* Deuxième item";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies blockquote conversion (> text → <quote>text</quote>).
     */
    public function test_converts_blockquotes(): void
    {
        $markdown = '> Citation importante';
        $expected = '<quote>Citation importante</quote>';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that all formatting types are converted properly
     * in a complex Markdown document combining multiple syntaxes.
     */
    public function test_converts_complex_markdown(): void
    {
        $markdown = "# Article\n\n**Introduction** en *gras* et italique.\n\n- Liste 1\n- Liste 2\n\n[Lien](https://example.com)\n\n> Citation";
        $expected = "{{{Article}}}\n\n{{Introduction}} en {gras} et italique.\n\n-* Liste 1\n-* Liste 2\n\n[Lien->https://example.com]\n\n<quote>Citation</quote>";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that empty strings are handled properly
     * and return an empty string without error.
     */
    public function test_handles_empty_string(): void
    {
        $this->assertEquals('', MarkdownToSpipConverter::convert(''));
    }

    /**
     * Verifies that text without any Markdown formatting is returned unchanged.
     */
    public function test_handles_text_without_markdown(): void
    {
        $text = 'Texte simple sans formatage';
        $this->assertEquals($text, MarkdownToSpipConverter::convert($text));
    }

    /**
     * Verifies inline code conversion (`code` → <code>code</code>).
     */
    public function test_converts_inline_code(): void
    {
        $markdown = 'Utiliser `echo` pour afficher';
        $expected = 'Utiliser <code>echo</code> pour afficher';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies code block conversion (``` → <code>...</code>).
     */
    public function test_converts_code_blocks(): void
    {
        $markdown = "Exemple de code:\n\n```\nfunction hello() {\n  return 'world';\n}\n```";
        $expected = "Exemple de code:\n\n<code>\nfunction hello() {\n  return 'world';\n}\n</code>";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that code block contents are protected
     * and inner Markdown syntax is not converted.
     */
    public function test_code_blocks_protect_content_from_conversion(): void
    {
        $markdown = '```**gras** et *italique*```';
        $expected = '<code>**gras** et *italique*</code>';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies footnote conversion ([^ref] → [[note]]) with definition removal.
     */
    public function test_converts_footnotes(): void
    {
        $markdown = "Texte avec note[^1] et autre note[^2].\n\n[^1]: Première note\n[^2]: Deuxième note";
        $expected = "Texte avec note[[Première note]] et autre note[[Deuxième note]].\n\n\n";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies the conversion of a single footnote with reference and definition.
     */
    public function test_converts_single_footnote(): void
    {
        $markdown = "Un texte[^ref].\n\n[^ref]: Contenu de la note";
        $expected = "Un texte[[Contenu de la note]].\n\n";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that footnote references without a definition
     * stay unchanged in the converted text.
     */
    public function test_footnote_without_definition_stays_unchanged(): void
    {
        $markdown = 'Texte avec référence[^1] sans définition.';
        $expected = 'Texte avec référence[^1] sans définition.';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies footnote conversion in a complex document
     * combining headings, formatting and footnotes.
     */
    public function test_complex_document_with_footnotes(): void
    {
        $markdown = "# Titre\n\nParagraphe avec note[^1].\n\n## Sous-titre\n\n**Gras** avec note[^2].\n\n[^1]: Note importante\n[^2]: Autre note";
        $expected = "{{{Titre}}}\n\nParagraphe avec note[[Note importante]].\n\n{{Sous-titre}}\n\n{{Gras}} avec note[[Autre note]].\n\n\n";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that code blocks with a language name (```js, ```php)
     * strip the language name when converted.
     */
    public function test_code_blocks_ignore_language_name(): void
    {
        $markdown = "```js\nconst x = 42;\n```";
        $expected = "<code>\nconst x = 42;\n</code>";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies italic conversion with underscore (_text_ → {text}).
     */
    public function test_converts_italic_with_underscore(): void
    {
        $markdown = '_texte en italique_';
        $expected = '{texte en italique}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies bold conversion with double underscore (__text__ → {{text}}).
     */
    public function test_converts_bold_with_underscore(): void
    {
        $markdown = '__texte en gras__';
        $expected = '{{texte en gras}}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies combined bold+italic conversion (***text*** → {{ { text } }}).
     */
    public function test_converts_bold_and_italic_combined_with_asterisks(): void
    {
        $markdown = '***texte gras et italique***';
        $expected = '{{ { texte gras et italique } }}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies combined bold+italic conversion (___text___ → {{ { text } }}).
     */
    public function test_converts_bold_and_italic_combined_with_underscores(): void
    {
        $markdown = '___texte gras et italique___';
        $expected = '{{ { texte gras et italique } }}';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies strikethrough conversion (~~text~~ → <del>text</del>).
     */
    public function test_converts_strikethrough(): void
    {
        $markdown = 'Texte avec ~~barré~~ dedans';
        $expected = 'Texte avec <del>barré</del> dedans';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies numbered list conversion (1. item → -# item).
     */
    public function test_converts_numbered_lists(): void
    {
        $markdown = "1. Premier\n2. Deuxième\n10) Dixième";
        $expected = "-# Premier\n-# Deuxième\n-# Dixième";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that * and + bullets are converted like - bullets.
     */
    public function test_converts_star_and_plus_bullets(): void
    {
        $markdown = "* Étoile\n+ Plus";
        $expected = "-* Étoile\n-* Plus";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that indented items become nested SPIP items, whatever the
     * indentation width, and that each level keeps its own list type.
     */
    public function test_converts_nested_lists(): void
    {
        $markdown = "1. Étape\n   - Détail\n     - Précision\n   - Autre détail\n2. Étape suivante\n\n"
            ."- Puce\n    1. Sous-étape";
        $expected = "-# Étape\n-** Détail\n-*** Précision\n-** Autre détail\n-# Étape suivante\n\n"
            ."-* Puce\n-## Sous-étape";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that emphasis inside list items is still converted.
     */
    public function test_converts_emphasis_inside_list_items(): void
    {
        $markdown = "* Un **gras** et un *italique*\n  * Imbriqué";
        $expected = "-* Un {{gras}} et un {italique}\n-** Imbriqué";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies horizontal rule conversion (---, ***, ___ → ----).
     */
    public function test_converts_horizontal_rules(): void
    {
        $markdown = "Avant\n\n---\n\n***\n\n_ _ _\n\nAprès";
        $expected = "Avant\n\n----\n\n----\n\n----\n\nAprès";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies image conversion (![alt](url) → [alt->url]).
     */
    public function test_converts_images_to_links(): void
    {
        $markdown = '![Logo SPIP](https://www.spip.net/logo.png "Le logo")';
        $expected = '[Logo SPIP->https://www.spip.net/logo.png]';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that link titles are dropped ([text](url "title") → [text->url]).
     */
    public function test_converts_links_with_title(): void
    {
        $markdown = '[SPIP](https://www.spip.net "Site officiel")';
        $expected = '[SPIP->https://www.spip.net]';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies autolink conversion (<url> → [->url]).
     */
    public function test_converts_autolinks(): void
    {
        $markdown = 'Voir <https://www.spip.net> pour la doc';
        $expected = 'Voir [->https://www.spip.net] pour la doc';

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies table conversion: bold header row, separator row removed.
     */
    public function test_converts_tables(): void
    {
        $markdown = "Avant\n\n| Nom | **Rôle** |\n|:---|---:|\n| Ada | *Code* |\n\nAprès";
        $expected = "Avant\n\n| {{Nom}} | {{Rôle}} |\n| Ada | {Code} |\n\nAprès";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies table conversion when outer pipes are omitted.
     */
    public function test_converts_tables_without_outer_pipes(): void
    {
        $markdown = "Nom | Rôle\n--- | ---\nAda | Code";
        $expected = "| {{Nom}} | {{Rôle}} |\n| Ada | Code |";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that inline code inside table cells is kept, pipes included.
     */
    public function test_converts_tables_with_inline_code(): void
    {
        $markdown = "| Markdown | SPIP |\n|---|---|\n| `**a**` | `a|b` |";
        $expected = "| {{Markdown}} | {{SPIP}} |\n| <code>**a**</code> | <code>a|b</code> |";

        $this->assertEquals($expected, MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * Verifies that underscores inside words are not read as italics.
     */
    public function test_keeps_underscores_inside_words(): void
    {
        $text = 'Le fichier mon_fichier et la variable autre_chose ou été_là';

        $this->assertEquals($text, MarkdownToSpipConverter::convert($text));
    }

    /**
     * Verifies that isolated asterisks are not read as italics.
     */
    public function test_keeps_isolated_asterisks(): void
    {
        $text = 'Calcul : 2 * 3 et 4 * 5';

        $this->assertEquals($text, MarkdownToSpipConverter::convert($text));
    }

    /**
     * Verifies that emphasis never spans several lines.
     */
    public function test_emphasis_does_not_span_lines(): void
    {
        $text = "Un *début\nsans fin* ici";

        $this->assertEquals($text, MarkdownToSpipConverter::convert($text));
    }
}
