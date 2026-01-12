# Markdown to SPIP

Convertisseur en ligne Markdown vers syntaxe SPIP, en temps réel.

**[https://markdown2spip.orsal.fr](https://markdown2spip.orsal.fr)**

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
| `**gras**` | `{{gras}}` | Texte en gras |
| `*italique*` | `{italique}` | Texte en italique |
| `` `code` `` | `<code>code</code>` | Code inline |
| ` ```code``` ` | `<code>code</code>` | Blocs de code |
| `[lien](url)` | `[lien->url]` | Liens hypertextes |
| `- item` | `-* item` | Listes à puces |
| `> citation` | `<quote>citation</quote>` | Citations/blockquotes |
| `Texte[^1]` + `[^1]: note` | `Texte[[note]]` | Notes de bas de page |

**Note :** Le contenu des blocs de code est protégé et n'est pas transformé par les autres règles de conversion.

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
- `php artisan key:generate` (clé d'application)
- `php artisan migrate` (base de données SQLite)
- `npm install` (dépendances front-end)
- `npm run build` (compilation des assets)

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
     CONTACT_EMAIL=votre-email@domaine.fr
     ```
   - Dans `mentions-legales.blade.php`, modifiez les attributs `data-email-*` :
     ```html
     <span class="protected-email" data-email-user="votre-identifiant" data-email-domain="domaine.fr">
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
