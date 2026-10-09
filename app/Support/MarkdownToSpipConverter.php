<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Markdown to SPIP syntax converter.
 *
 * Transforms common Markdown elements (headings, bold, italic, links, lists, etc.)
 * to their equivalent in the SPIP publishing syntax.
 */
class MarkdownToSpipConverter
{
    /**
     * Stateless transformations, applied in order. The order is significant:
     * combined emphasis is matched before bold, and bold before italic, so that
     * the longest marker wins.
     *
     * @var array<string, string>
     */
    private const RULES = [
        // # Title → {{{Title}}}
        '/^#\s+(.+)$/mu' => '{{{$1}}}',
        // ## to ###### Subtitle → {{Subtitle}} (bold)
        '/^#{2,6}\s+(.+)$/mu' => '{{$1}}',
        // ~~text~~ → <del>text</del>
        '/~~(?!\s)([^\n]+?)(?<!\s)~~/u' => '<del>$1</del>',
        // ***text*** → {{ { text } }}
        '/(?<!\*)\*\*\*(?!\s)([^\n]+?)(?<!\s)\*\*\*(?!\*)/u' => '{{ { $1 } }}',
        // ___text___ → {{ { text } }}
        '/(?<![_\w])___(?!\s)([^\n]+?)(?<!\s)___(?![_\w])/u' => '{{ { $1 } }}',
        // **text** → {{text}}
        '/(?<!\*)\*\*(?!\s)([^\n]+?)(?<!\s)\*\*(?!\*)/u' => '{{$1}}',
        // __text__ → {{text}}
        '/(?<![_\w])__(?!\s)([^\n]+?)(?<!\s)__(?![_\w])/u' => '{{$1}}',
        // *text* → {text}
        '/(?<!\*)\*(?![\s*])([^*\n]+?)(?<![\s*])\*(?!\*)/u' => '{$1}',
        // _text_ → {text}, never inside a word (snake_case stays as is)
        '/(?<![_\w])_(?![\s_])([^_\n]+?)(?<![\s_])_(?![_\w])/u' => '{$1}',
        // ![alt](url "title") → [alt->url]: SPIP images are attached documents
        '/!\[([^\]]*)\]\(([^)\s]+)(?:\s+"[^"]*")?\)/u' => '[$1->$2]',
        // [text](url "title") → [text->url]
        '/\[([^\]]+)\]\(([^)\s]+)(?:\s+"[^"]*")?\)/u' => '[$1->$2]',
        // > text → <quote>text</quote>
        '/^>\s*(.+)$/mu' => '<quote>$1</quote>',
    ];

    private const LIST_ITEM = '/^([ \t]*)([-*+]|\d+[.)])[ \t]+(.*)$/';

    private const HORIZONTAL_RULE = '/^ {0,3}([-*_])(?:[ \t]*\1){2,}[ \t]*$/';

    private const TABLE_SEPARATOR = '/^[ \t]*\|?[ \t]*:?-+:?[ \t]*(?:\|[ \t]*:?-+:?[ \t]*)*\|?[ \t]*$/';

    /**
     * Convert Markdown text to SPIP syntax.
     *
     * @param  string  $markdown  The Markdown-formatted text to convert
     * @return string The text converted to SPIP syntax
     */
    public static function convert(string $markdown): string
    {
        ['text' => $spip, 'code' => $codeBlocks] = self::protectCode($markdown);

        $spip = self::resolveFootnotes($spip);

        $spip = self::convertBlocks($spip);

        foreach (self::RULES as $pattern => $replacement) {
            $spip = preg_replace($pattern, $replacement, $spip) ?? $spip;
        }

        return strtr($spip, $codeBlocks);
    }

    /**
     * Swap code blocks and inline code for placeholders, so the rules below
     * never rewrite their contents.
     *
     * @return array{
     *     text: string,
     *     code: array<string, string>
     * }
     */
    private static function protectCode(string $markdown): array
    {
        $codeBlocks = [];
        $index = 0;

        // Fenced blocks, dropping the optional language name (```js, ```php, etc.)
        $text = preg_replace_callback(
            '/```(?:\w+)?\n?(.+?)```/s',
            function (array $matches) use (&$codeBlocks, &$index): string {
                $placeholder = "\x00CODEBLOCK{$index}\x00";
                $content = $matches[1];

                $codeBlocks[$placeholder] = str_contains($content, "\n")
                    ? '<code>'."\n".trim($content)."\n".'</code>'
                    : '<code>'.$content.'</code>';

                $index++;

                return $placeholder;
            },
            $markdown
        ) ?? $markdown;

        $text = preg_replace_callback(
            '/`(.+?)`/',
            function (array $matches) use (&$codeBlocks, &$index): string {
                $placeholder = "\x00CODEBLOCK{$index}\x00";
                $codeBlocks[$placeholder] = '<code>'.$matches[1].'</code>';
                $index++;

                return $placeholder;
            },
            $text
        ) ?? $text;

        return ['text' => $text, 'code' => $codeBlocks];
    }

    /**
     * Move each footnote definition into the place where it is referenced.
     * References without a definition are left untouched.
     */
    private static function resolveFootnotes(string $text): string
    {
        $footnotes = [];

        $text = preg_replace_callback(
            '/^\[\^([^\]]+)\]:\s*(.+)$/m',
            function (array $matches) use (&$footnotes): string {
                $footnotes[$matches[1]] = trim($matches[2]);

                return '';
            },
            $text
        ) ?? $text;

        return preg_replace_callback(
            '/\[\^([^\]]+)\]/',
            function (array $matches) use (&$footnotes): string {
                $id = $matches[1];

                return isset($footnotes[$id]) ? '[['.$footnotes[$id].']]' : $matches[0];
            },
            $text
        ) ?? $text;
    }

    /**
     * Convert the line-based structures (horizontal rules, tables, lists)
     * before the inline rules, so that list bullets are not read as emphasis.
     */
    private static function convertBlocks(string $text): string
    {
        $lines = explode("\n", $text);
        $output = [];
        $indents = [];
        $count = count($lines);

        for ($i = 0; $i < $count; $i++) {
            $line = $lines[$i];

            if (preg_match(self::HORIZONTAL_RULE, $line)) {
                $output[] = '----';
                $indents = [];

                continue;
            }

            if (self::startsTable($line, $lines[$i + 1] ?? null)) {
                $output[] = self::tableRow($line, header: true);
                $i++;

                while ($i + 1 < $count && str_contains($lines[$i + 1], '|') && trim($lines[$i + 1]) !== '') {
                    $i++;
                    $output[] = self::tableRow($lines[$i]);
                }

                $indents = [];

                continue;
            }

            if (preg_match(self::LIST_ITEM, $line, $matches)) {
                $output[] = self::listItem($matches, $indents);

                continue;
            }

            if (trim($line) !== '') {
                $indents = [];
            }

            $output[] = $line;
        }

        return implode("\n", $output);
    }

    private static function startsTable(string $line, ?string $next): bool
    {
        if ($next === null) {
            return false;
        }

        if (! str_contains($line, '|')) {
            return false;
        }

        return str_contains($next, '|') && preg_match(self::TABLE_SEPARATOR, $next) === 1;
    }

    /**
     * Rewrite a Markdown table row as a SPIP one. Header cells are wrapped in
     * {{ }}, which is how SPIP marks a <thead>.
     */
    private static function tableRow(string $line, bool $header = false): string
    {
        $cells = explode('|', trim(trim($line, " \t"), '|'));

        $cells = array_map(function (string $cell) use ($header): string {
            $cell = trim($cell, " \t");

            if (! $header || $cell === '') {
                return $cell;
            }

            return '{{'.preg_replace('/^(\*\*|__)(.+)\1$/', '$2', $cell).'}}';
        }, $cells);

        return '| '.implode(' | ', $cells).' |';
    }

    /**
     * Turn a list item into a SPIP one, the nesting depth coming from the
     * indentation compared with the items above (-* , -** , -# , -## …).
     *
     * @param  array<int, string>  $matches
     * @param  array<int, int>  $indents  Indentation of each open level
     */
    private static function listItem(array $matches, array &$indents): string
    {
        $indent = strlen(str_replace("\t", '    ', $matches[1]));

        while ($indents !== [] && end($indents) > $indent) {
            array_pop($indents);
        }

        if ($indents === [] || end($indents) < $indent) {
            $indents[] = $indent;
        }

        $marker = ctype_digit($matches[2][0]) ? '#' : '*';

        return '-'.str_repeat($marker, count($indents)).' '.$matches[3];
    }
}
