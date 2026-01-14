# Markdown to SPIP

[![License: GPL-3.0](https://img.shields.io/badge/License-GPL%203.0-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![Tests](https://img.shields.io/badge/tests-50%20passed-success)](tests/)

Convertisseur en ligne Markdown vers syntaxe SPIP, en temps réel.

**[Démo en ligne](https://markdown2spip.orsal.fr)**

![Interface desktop du convertisseur Markdown vers SPIP](.github/assets/markdown-spip-converter-desktop-interface.png)

## Fonctionnalités

- **Conversion en temps réel** avec debounce de 50ms (pas de bouton "Convertir")
- **Interface split-view** : Markdown à gauche, SPIP à droite
- **Copie en un clic** vers le presse-papier avec feedback visuel
- **Persistance locale** : Texte sauvegardé dans localStorage du navigateur (navigation sans perte)
- **Compteur de caractères** en temps réel (limite : 100 000 caractères)
- **Aide contextuelle** avec popover expliquant les conversions supportées
- **Aucun stockage serveur** (RGPD-friendly) - données uniquement dans votre navigateur
- **Rate limiting** : 300 requêtes par minute par IP
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

## Stack technique

- Laravel 12
- Livewire 3
- Tailwind CSS 4
- Alpine.js

## Installation locale

```bash
git clone https://github.com/Gallyan/md2spip.git
cd md2spip
composer run setup
```

Cette commande exécute automatiquement :
- `composer install` (dépendances PHP)
- Copie de `.env.example` vers `.env`
- Copie de `mentions-legales.EXAMPLE.blade.php` vers `mentions-legales.blade.php`
- `php artisan key:generate` (clé d'application Laravel)
- `npm install` (dépendances front-end)
- `npm run build` (compilation des assets Tailwind/Vite)

Lancez ensuite le serveur de développement :
```bash
php artisan serve
```

### Mentions légales

Personnalisez `resources/views/mentions-legales.blade.php` (créé automatiquement depuis le template `.EXAMPLE`) avec vos informations et votre email de contact dans `.env` (`CONTACT_EMAIL`).

## Tests

```bash
php artisan test
```

Pour plus de détails sur l'évolution du projet, consultez le [CHANGELOG](CHANGELOG.md).

## Accessibilité

- **Lighthouse** : 100/100 (Performance, Accessibilité, Best Practices, SEO)
- Navigation au clavier complète
- Support ARIA et lecteurs d'écran
- Aucune ressource externe (pas de CDN, Google Fonts)

## CI/CD

Le projet utilise GitHub Actions pour l'intégration continue. Le workflow se déclenche automatiquement sur push et pull request vers `main`.

Le workflow exécute :
- **Tests** (PHP 8.2, 8.3 et 8.4 avec PHPUnit - 50 tests)
- **PHPStan level 6** (analyse statique)

## Sécurité

- **Rate limiting** : 300 requêtes/minute par IP
- **Validation** : Limite de 100 000 caractères
- **En-têtes de sécurité** : CSP, X-Frame-Options, HSTS, Permissions-Policy
- **Cookies sécurisés** : httpOnly, secure, sameSite=strict
- **Pas de base de données** : Aucune donnée utilisateur stockée
