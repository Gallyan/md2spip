<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Convertisseur Markdown vers syntaxe SPIP.
 *
 * Transforme les éléments Markdown courants (titres, gras, italique, liens, listes, etc.)
 * vers leur équivalent dans la syntaxe de publication SPIP.
 */
class MarkdownToSpipConverter
{
    /**
     * Convertit du texte Markdown en syntaxe SPIP.
     *
     * @param  string  $markdown  Le texte au format Markdown à convertir
     * @return string Le texte converti en syntaxe SPIP
     */
    public static function convert(string $markdown): string
    {
        $spip = $markdown;
        $codeBlocks = [];
        $codeIndex = 0;

        // Extraire et protéger les blocs de code avec des placeholders
        // Supprimer le nom du langage optionnel (```js, ```php, etc.)
        $spip = preg_replace_callback('/```(?:\w+)?\n?(.+?)```/s', function ($matches) use (&$codeBlocks, &$codeIndex) {
            $placeholder = "\x00CODEBLOCK{$codeIndex}\x00";
            $content = $matches[1];
            // Ajouter des sauts de ligne uniquement si le contenu est multiligne
            if (str_contains($content, "\n")) {
                $codeBlocks[$placeholder] = '<code>'."\n".trim($content)."\n".'</code>';
            } else {
                $codeBlocks[$placeholder] = '<code>'.$content.'</code>';
            }
            $codeIndex++;

            return $placeholder;
        }, $spip) ?? $spip;

        // Extraire et protéger le code inline avec des placeholders
        $spip = preg_replace_callback('/`(.+?)`/', function ($matches) use (&$codeBlocks, &$codeIndex) {
            $placeholder = "\x00CODEBLOCK{$codeIndex}\x00";
            $codeBlocks[$placeholder] = '<code>'.$matches[1].'</code>';
            $codeIndex++;

            return $placeholder;
        }, $spip) ?? $spip;

        // Notes de bas de page : extraire les définitions [^id]: texte
        $footnotes = [];
        $spip = preg_replace_callback('/^\[\^([^\]]+)\]:\s*(.+)$/m', function ($matches) use (&$footnotes) {
            $footnotes[$matches[1]] = trim($matches[2]);

            return ''; // Supprimer la ligne de définition
        }, $spip) ?? $spip;

        // Remplacer les références [^id] par [[texte]]
        $spip = preg_replace_callback('/\[\^([^\]]+)\]/', function ($matches) use ($footnotes) {
            $id = $matches[1];
            if (isset($footnotes[$id])) {
                return '[['.$footnotes[$id].']]';
            }

            return $matches[0]; // Garder tel quel si pas de définition trouvée
        }, $spip) ?? $spip;

        // Titres niveau 1 : # Titre → {{{Titre}}}
        $spip = preg_replace('/^#\s+(.+)$/m', '{{{$1}}}', $spip) ?? $spip;

        // Titres niveaux 2+ : ## à ###### → {{Titre}} (en gras)
        $spip = preg_replace('/^#{2,6}\s+(.+)$/m', '{{$1}}', $spip) ?? $spip;

        // Barré ~~texte~~ → <del>texte</del>
        $spip = preg_replace('/~~(.+?)~~/s', '<del>$1</del>', $spip) ?? $spip;

        // Gras+Italique combiné ***texte*** ou ___texte___ → {{ { texte } }}
        $spip = preg_replace('/\*\*\*(.+?)\*\*\*/s', '{{ { $1 } }}', $spip) ?? $spip;
        $spip = preg_replace('/___(.+?)___/s', '{{ { $1 } }}', $spip) ?? $spip;

        // Gras **texte** ou __texte__ → {{texte}}
        $spip = preg_replace('/\*\*(.+?)\*\*/s', '{{$1}}', $spip) ?? $spip;
        $spip = preg_replace('/__(.+?)__/s', '{{$1}}', $spip) ?? $spip;

        // Italique *texte* ou _texte_ → {texte}
        $spip = preg_replace('/\*(.+?)\*/s', '{$1}', $spip) ?? $spip;
        $spip = preg_replace('/_(.+?)_/s', '{$1}', $spip) ?? $spip;

        // Liens [texte](url) → [texte->url]
        $spip = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '[$1->$2]', $spip) ?? $spip;

        // Listes - item → -* item
        $spip = preg_replace('/^-\s+/m', '-* ', $spip) ?? $spip;

        // Citations > texte → <quote>texte</quote>
        $spip = preg_replace('/^>\s*(.+)$/m', '<quote>$1</quote>', $spip) ?? $spip;

        // Restaurer les blocs de code
        foreach ($codeBlocks as $placeholder => $code) {
            $spip = str_replace($placeholder, $code, $spip);
        }

        return $spip;
    }
}
