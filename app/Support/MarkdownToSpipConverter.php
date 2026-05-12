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
     * Convert Markdown text to SPIP syntax.
     *
     * @param  string  $markdown  The Markdown-formatted text to convert
     * @return string The text converted to SPIP syntax
     */
    public static function convert(string $markdown): string
    {
        $spip = $markdown;
        $codeBlocks = [];
        $codeIndex = 0;

        // Extract and protect code blocks with placeholders
        // Strip the optional language name (```js, ```php, etc.)
        $spip = preg_replace_callback('/```(?:\w+)?\n?(.+?)```/s', function ($matches) use (&$codeBlocks, &$codeIndex) {
            $placeholder = "\x00CODEBLOCK{$codeIndex}\x00";
            $content = $matches[1];
            // Add line breaks only if content is multi-line
            if (str_contains($content, "\n")) {
                $codeBlocks[$placeholder] = '<code>'."\n".trim($content)."\n".'</code>';
            } else {
                $codeBlocks[$placeholder] = '<code>'.$content.'</code>';
            }
            $codeIndex++;

            return $placeholder;
        }, $spip) ?? $spip;

        // Extract and protect inline code with placeholders
        $spip = preg_replace_callback('/`(.+?)`/', function ($matches) use (&$codeBlocks, &$codeIndex) {
            $placeholder = "\x00CODEBLOCK{$codeIndex}\x00";
            $codeBlocks[$placeholder] = '<code>'.$matches[1].'</code>';
            $codeIndex++;

            return $placeholder;
        }, $spip) ?? $spip;

        // Footnotes: extract definitions [^id]: text
        $footnotes = [];
        $spip = preg_replace_callback('/^\[\^([^\]]+)\]:\s*(.+)$/m', function ($matches) use (&$footnotes) {
            $footnotes[$matches[1]] = trim($matches[2]);

            return ''; // Remove the definition line
        }, $spip) ?? $spip;

        // Replace references [^id] with [[text]]
        $spip = preg_replace_callback('/\[\^([^\]]+)\]/', function ($matches) use ($footnotes) {
            $id = $matches[1];
            if (isset($footnotes[$id])) {
                return '[['.$footnotes[$id].']]';
            }

            return $matches[0]; // Keep as-is if no definition found
        }, $spip) ?? $spip;

        // Level 1 headings: # Title → {{{Title}}}
        $spip = preg_replace('/^#\s+(.+)$/m', '{{{$1}}}', $spip) ?? $spip;

        // Level 2+ headings: ## to ###### → {{Title}} (bold)
        $spip = preg_replace('/^#{2,6}\s+(.+)$/m', '{{$1}}', $spip) ?? $spip;

        // Strikethrough ~~text~~ → <del>text</del>
        $spip = preg_replace('/~~(.+?)~~/s', '<del>$1</del>', $spip) ?? $spip;

        // Combined bold+italic ***text*** or ___text___ → {{ { text } }}
        $spip = preg_replace('/\*\*\*(.+?)\*\*\*/s', '{{ { $1 } }}', $spip) ?? $spip;
        $spip = preg_replace('/___(.+?)___/s', '{{ { $1 } }}', $spip) ?? $spip;

        // Bold **text** or __text__ → {{text}}
        $spip = preg_replace('/\*\*(.+?)\*\*/s', '{{$1}}', $spip) ?? $spip;
        $spip = preg_replace('/__(.+?)__/s', '{{$1}}', $spip) ?? $spip;

        // Italic *text* or _text_ → {text}
        $spip = preg_replace('/\*(.+?)\*/s', '{$1}', $spip) ?? $spip;
        $spip = preg_replace('/_(.+?)_/s', '{$1}', $spip) ?? $spip;

        // Links [text](url) → [text->url]
        $spip = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '[$1->$2]', $spip) ?? $spip;

        // Lists - item → -* item
        $spip = preg_replace('/^-\s+/m', '-* ', $spip) ?? $spip;

        // Blockquotes > text → <quote>text</quote>
        $spip = preg_replace('/^>\s*(.+)$/m', '<quote>$1</quote>', $spip) ?? $spip;

        // Restore code blocks
        foreach ($codeBlocks as $placeholder => $code) {
            $spip = str_replace($placeholder, $code, $spip);
        }

        return $spip;
    }
}
