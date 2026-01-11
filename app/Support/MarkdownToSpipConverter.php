<?php

namespace App\Support;

class MarkdownToSpipConverter
{
    public static function convert(string $markdown): string
    {
        $spip = $markdown;

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

        return $spip;
    }
}
