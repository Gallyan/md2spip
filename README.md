# Markdown to SPIP

[![License: GPL-3.0](https://img.shields.io/badge/License-GPL%203.0-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![Tests](https://img.shields.io/badge/tests-51%20passed-success)](tests/)
[![Lighthouse](https://img.shields.io/badge/Lighthouse-100%2F100-success?logo=lighthouse)](https://markdown2spip.orsal.fr)
[![Accessibility](https://img.shields.io/badge/A11y-100%2F100-success)](https://markdown2spip.orsal.fr)

Convertisseur en ligne Markdown vers syntaxe SPIP, en temps réel.

**[✨ Démo en ligne](https://markdown2spip.orsal.fr)**

![Interface desktop du convertisseur Markdown vers SPIP](.github/assets/markdown-spip-converter-desktop-interface.png)

## Fonctionnalités

- **Conversion en temps réel** avec debounce de 50ms (pas de bouton "Convertir")
- **Interface split-view** : Markdown à gauche, SPIP à droite
- **Copie en un clic** vers le presse-papier avec feedback visuel
- **Persistance locale** : Texte sauvegardé dans localStorage du navigateur (navigation sans perte)
- **Compteur de caractères** en temps réel (limite : 100 000 caractères)
- **Compteur de requêtes** par minute (fenêtre glissante de 60 secondes)
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
- TailwindCSS
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

### Configuration des mentions légales

Après l'installation, un fichier `resources/views/mentions-legales.blade.php` est automatiquement créé depuis le template `.EXAMPLE`. Vous devez le personnaliser avec vos informations :

1. **Informations légales** : Éditeur du site, hébergeur, SIRET, adresse, etc.

2. **Email de contact obfusqué** :
   - Définissez votre email dans `.env` :
     ```env
     CONTACT_EMAIL=votre-email@example.com
     ```
   - Dans `mentions-legales.blade.php`, modifiez les attributs `data-email-*` :
     ```html
     <span class="protected-email" data-email-user="votre-identifiant" data-email-domain="example.com">
     ```

   L'email est protégé contre les robots spammeurs par obfuscation CSS tout en restant cliquable via la route `/contact-email`.

   Pour plus de détails sur la méthode d'obfuscation utilisée, consultez l'article : [Obfuscation d'email : CSS vs JavaScript](https://www.orsal.fr/Obfuscation-d-email-CSS-vs)

**Note :** Le fichier `mentions-legales.blade.php` est dans `.gitignore` pour protéger vos informations personnelles. Seul le template `.EXAMPLE` est versionné.

## Développement

```bash
npm run dev
```

## Tests

```bash
# Lancer tous les tests
php artisan test

# Tests unitaires uniquement
php artisan test --testsuite=Unit

# Tests fonctionnels uniquement
php artisan test --testsuite=Feature
```

**Couverture :** 51 tests / 84 assertions

Pour plus de détails sur l'évolution du projet, consultez le [CHANGELOG](CHANGELOG.md).

## Accessibilité

**Scores Lighthouse : 100/100 partout ! 🎉**
- ✅ **Performance : 100/100**
- ✅ **Accessibilité : 100/100**
- ✅ **Best Practices : 100/100**
- ✅ **SEO : 100/100**

**Fonctionnalités d'accessibilité :**
- Attributs ARIA complets sur tous les éléments interactifs
- Focus visible amélioré (ring emerald-500) sur tous les contrôles
- Navigation au clavier : Tab traverse tous les boutons et champs
- Annonces vocales : aria-live sur les compteurs et feedbacks (copié, effacé)
- Modales accessibles : role="dialog", aria-modal, support Échap
- Icônes décoratives : aria-hidden="true" pour les lecteurs d'écran
- Labels explicites : aria-label sur tous les boutons icon-only

### Auditer avec Lighthouse

**Chrome DevTools** :
1. Ouvrir Chrome DevTools (F12)
2. Onglet "Lighthouse"
3. Sélectionner les catégories (Accessibility, Performance, SEO...)
4. Cliquer "Analyze page load"

**Note** : L'application n'utilise aucune ressource externe (pas de CDN, Google Fonts, etc.), ce qui explique le message "no origins were preconnected" - c'est positif pour la performance et la vie privée.

## CI/CD

Le projet utilise GitHub Actions pour l'intégration continue :

```bash
# Lancer manuellement le workflow depuis l'interface GitHub
# Actions → Tests & Quality → Run workflow
```

Le workflow exécute :
- **Tests** (PHP 8.2, 8.3 et 8.4 avec PHPUnit - 51 tests, 84 assertions)
- **PHPStan level 6** (analyse statique - 0 erreur)

Pour activer les déclenchements automatiques sur push/PR, décommentez les lignes correspondantes dans `.github/workflows/tests.yml`.

## Sécurité

- **Rate limiting** : 300 requêtes par minute par IP (fenêtre glissante de 60 secondes)
- **Validation des entrées** : Limite de 100 000 caractères
- **En-têtes de sécurité** (via middleware Laravel intelligent) :
  - `X-Frame-Options: SAMEORIGIN` (protection clickjacking)
  - `X-Content-Type-Options: nosniff` (protection MIME sniffing)
  - `Content-Security-Policy` (CSP avec `'unsafe-inline'` et `'unsafe-eval'` pour Alpine.js)
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Permissions-Policy` (désactivation APIs sensibles : geolocation, camera, microphone, etc.)
  - `Strict-Transport-Security` (HSTS) géré par Apache en production HTTPS
  - **Middleware intelligent** : Détecte et respecte les en-têtes définis par Apache (pas de duplication)
- **Cookies sécurisés** : httpOnly, secure, sameSite=strict
- **Pas de base de données** : Aucune donnée utilisateur stockée
- **Cache éphémère** : Timestamps de requêtes conservés 70 secondes maximum
