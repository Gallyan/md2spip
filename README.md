# Markdown to SPIP

Convertisseur en ligne Markdown vers syntaxe SPIP, en temps réel.

**[https://md2spip.orsal.net](https://md2spip.orsal.net)**

## Fonctionnalités

- Conversion en temps réel (pas de bouton "Convertir")
- Interface split-view : Markdown à gauche, SPIP à droite
- Copie en un clic vers le presse-papier
- Aucun stockage de données (RGPD-friendly)
- Responsive (mobile/desktop)

## Conversions supportées

| Markdown | SPIP |
|----------|------|
| `# Titre` | `{{{Titre}}}` |
| `**gras**` | `{{gras}}` |
| `*italique*` | `{italique}` |
| `[lien](url)` | `[lien->url]` |
| `- item` | `-* item` |
| `> citation` | `<quote>citation</quote>` |

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

## Licence

MIT
