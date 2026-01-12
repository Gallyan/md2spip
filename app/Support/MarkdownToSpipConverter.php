<?php

namespace App\Support;

class MarkdownToSpipConverter
{
    public static function convert(string $markdown): string
    {
        $spip = $markdown;
        $codeBlocks = [];
        $codeIndex = 0;

        // Extraire et protéger les blocs de code avec des placeholders
        $spip = preg_replace_callback('/```(.+?)```/s', function($matches) use (&$codeBlocks, &$codeIndex) {
            $placeholder = "___CODE_BLOCK_{$codeIndex}___";
            $codeBlocks[$placeholder] = '<code>' . $matches[1] . '</code>';
            $codeIndex++;
            return $placeholder;
        }, $spip);

        // Extraire et protéger le code inline avec des placeholders
        $spip = preg_replace_callback('/`(.+?)`/', function($matches) use (&$codeBlocks, &$codeIndex) {
            $placeholder = "___CODE_BLOCK_{$codeIndex}___";
            $codeBlocks[$placeholder] = '<code>' . $matches[1] . '</code>';
            $codeIndex++;
            return $placeholder;
        }, $spip);

        // Titres (# à ######) → {{{Titre}}}
        $spip = preg_replace('/^#{1,6}\s+(.+)$/m', '{{{$1}}}', $spip);

        // Gras **texte** → {{texte}}
        $spip = preg_replace('/\*\*(.+?)\*\*/s', '{{$1}}', $spip);

        // Italique *texte* → {texte}
        $spip = preg_replace('/\*(.+?)\*/s', '{$1}', $spip);

        // Liens [texte](url) → [texte->url]
        $spip = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '[$1->$2]', $spip);

        // Listes - item → -* item
        $spip = preg_replace('/^-\s+/m', '-* ', $spip);

        // Citations > texte → <quote>texte</quote>
        $spip = preg_replace('/^>\s*(.+)$/m', '<quote>$1</quote>', $spip);

        // Restaurer les blocs de code
        foreach ($codeBlocks as $placeholder => $code) {
            $spip = str_replace($placeholder, $code, $spip);
        }

        return $spip;
    }
}
