# Markdown to SPIP

[![License: GPL-3.0](https://img.shields.io/badge/License-GPL%203.0-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![Livewire](https://img.shields.io/badge/Livewire-3-FB70A9?logo=livewire&logoColor=white)](https://livewire.laravel.com/)

Convertisseur en ligne Markdown vers syntaxe SPIP, en temps réel.

**[Démo en ligne](https://markdown2spip.orsal.fr)**

![Interface desktop du convertisseur Markdown vers SPIP](.github/assets/markdown-spip-converter-desktop-interface.png)

## Fonctionnalités

- **Conversion en temps réel** (pas de bouton "Convertir")
- **Interface split-view** : Markdown à gauche, SPIP à droite
- **Copie en un clic** vers le presse-papier avec feedback visuel
- **Persistance locale** : Texte sauvegardé dans localStorage du navigateur (navigation sans perte)
- **Compteur de caractères** en temps réel
- **Aide contextuelle** avec popover expliquant les conversions supportées
- **Responsive** (mobile/desktop)
- **Mode sombre** par défaut

## Conversions supportées

| Markdown | SPIP | Description |
|----------|------|-------------|
| `# Titre` | `{{{Titre}}}` | Titre principal (h1 uniquement) |
| `## Sous-titre` | `{{Sous-titre}}` | Sous-titres (h2 à h6) en gras |
| `**gras**` ou `__gras__` | `{{gras}}` | Texte en gras |
| `*italique*` ou `_italique_` | `{italique}` | Texte en italique |
| `***gras+ita***` ou `___gras+ita___` | `{{ { gras+ita } }}` | Gras et italique combinés |
| `~~barré~~` | `<del>barré</del>` | Texte barré |
| `` `code` `` | `<code>code</code>` | Code inline |
| ` ```code``` ` ou ` ```js code``` ` | `<code>code</code>` | Blocs de code (nom langage ignoré) |
| `[lien](url)` | `[lien->url]` | Liens hypertextes |
| `- item` | `-* item` | Listes à puces |
| `> citation` | `<quote>citation</quote>` | Citations/blockquotes |
| `Texte[^1]` + `[^1]: note` | `Texte[[note]]` | Notes de bas de page |

**Notes :**
- Le contenu des blocs de code est protégé et n'est pas transformé par les autres règles de conversion.
- Les blocs de code peuvent inclure un nom de langage (```js, ```php, etc.) qui sera automatiquement supprimé.
- L'underscore `_` fonctionne exactement comme l'astérisque `*` pour le formatage.

## Installation locale

```bash
git clone https://github.com/Gallyan/md2spip.git
cd md2spip
composer run setup
php artisan serve
```

Personnalisez `resources/views/mentions-legales.blade.php` avec vos informations légales et `CONTACT_EMAIL` dans `.env`.

## Tests

```bash
php artisan test
```

Pour plus de détails sur l'évolution du projet, consultez le [CHANGELOG](CHANGELOG.md).

## Accessibilité

- Navigation au clavier complète
- Support ARIA et lecteurs d'écran

## Vie privée

Aucune donnée utilisateur n'est stockée côté serveur. Tout reste dans votre navigateur.
