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
        '/^#\s+(.+)$/m' => '{{{$1}}}',
        '/^#{2,6}\s+(.+)$/m' => '{{$1}}',
        '/~~(.+?)~~/s' => '<del>$1</del>',
        '/\*\*\*(.+?)\*\*\*/s' => '{{ { $1 } }}',
        '/___(.+?)___/s' => '{{ { $1 } }}',
        '/\*\*(.+?)\*\*/s' => '{{$1}}',
        '/__(.+?)__/s' => '{{$1}}',
        '/\*(.+?)\*/s' => '{$1}',
        '/_(.+?)_/s' => '{$1}',
        '/\[([^\]]+)\]\(([^)]+)\)/' => '[$1->$2]',
        '/^-\s+/m' => '-* ',
        '/^>\s*(.+)$/m' => '<quote>$1</quote>',
    ];

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
        $text = preg_replace_callback('/```(?:\w+)?\n?(.+?)```/s', function (array $matches) use (&$codeBlocks, &$index): string {
            $placeholder = "\x00CODEBLOCK{$index}\x00";
            $content = $matches[1];

            $codeBlocks[$placeholder] = str_contains($content, "\n")
                ? '<code>'."\n".trim($content)."\n".'</code>'
                : '<code>'.$content.'</code>';

            $index++;

            return $placeholder;
        }, $markdown) ?? $markdown;

        $text = preg_replace_callback('/`(.+?)`/', function (array $matches) use (&$codeBlocks, &$index): string {
            $placeholder = "\x00CODEBLOCK{$index}\x00";
            $codeBlocks[$placeholder] = '<code>'.$matches[1].'</code>';
            $index++;

            return $placeholder;
        }, $text) ?? $text;

        return ['text' => $text, 'code' => $codeBlocks];
    }

    /**
     * Move each footnote definition into the place where it is referenced.
     * References without a definition are left untouched.
     */
    private static function resolveFootnotes(string $text): string
    {
        $footnotes = [];

        $text = preg_replace_callback('/^\[\^([^\]]+)\]:\s*(.+)$/m', function (array $matches) use (&$footnotes): string {
            $footnotes[$matches[1]] = trim($matches[2]);

            return '';
        }, $text) ?? $text;

        return preg_replace_callback('/\[\^([^\]]+)\]/', function (array $matches) use (&$footnotes): string {
            $id = $matches[1];

            return isset($footnotes[$id]) ? '[['.$footnotes[$id].']]' : $matches[0];
        }, $text) ?? $text;
    }
}
