# Markdown to SPIP

Convertisseur en ligne Markdown vers syntaxe SPIP, en temps réel.

**[https://md2spip.orsal.net](https://md2spip.orsal.net)**

## Fonctionnalités

- **Conversion en temps réel** avec debounce de 50ms (pas de bouton "Convertir")
- **Interface split-view** : Markdown à gauche, SPIP à droite
- **Copie en un clic** vers le presse-papier avec feedback visuel
- **Compteur de caractères** en temps réel (limite : 100 000 caractères)
- **Compteur de requêtes** par minute (fenêtre glissante de 60 secondes)
- **Aide contextuelle** avec popover expliquant les conversions supportées
- **Aucun stockage de données** (RGPD-friendly)
- **Rate limiting** : 300 requêtes par minute par IP
- **Responsive** (mobile/desktop)
- **Mode sombre** par défaut

## Conversions supportées

| Markdown | SPIP | Description |
|----------|------|-------------|
| `# Titre` | `{{{Titre}}}` | Titres (h1 à h6) |
| `**gras**` | `{{gras}}` | Texte en gras |
| `*italique*` | `{italique}` | Texte en italique |
| `` `code` `` | `<code>code</code>` | Code inline |
| ` ```code``` ` | `<code>code</code>` | Blocs de code |
| `[lien](url)` | `[lien->url]` | Liens hypertextes |
| `- item` | `-* item` | Listes à puces |
| `> citation` | `<quote>citation</quote>` | Citations/blockquotes |

**Note :** Le contenu des blocs de code est protégé et n'est pas transformé par les autres règles de conversion.

## Stack technique

- Laravel 12
- Livewire 3
- TailwindCSS
- Alpine.js

## Installation locale

```bash
git clone https://github.com/votre-user/md2spip.git
cd md2spip
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run build
php artisan serve
```

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

**Couverture :** 39 tests / 62 assertions

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

> **Note :** Voir [APACHE-SECURITY.md](APACHE-SECURITY.md) pour la configuration Apache recommandée
