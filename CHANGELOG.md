# Changelog

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/),
et ce projet adhère au [Semantic Versioning](https://semver.org/lang/fr/).

## [1.0.0] - 2026-01-14

Première version stable du convertisseur Markdown vers SPIP.

### Conversion Markdown vers SPIP

- Titres H1 (`# Titre` → `{{{Titre}}}`)
- Sous-titres H2-H6 (`## Sous-titre` → `{{Sous-titre}}`)
- Gras (`**gras**` → `{{gras}}`)
- Italique (`*italique*` → `{italique}`)
- Gras et italique combinés (`***texte***` → `{{{texte}}}`)
- Texte barré (`~~barré~~` → `<del>barré</del>`)
- Code inline (`` `code` `` → `<code>code</code>`)
- Blocs de code avec suppression automatique de l'identifiant de langage
- Liens (`[texte](url)` → `[texte->url]`)
- Listes à puces (`- item` → `-* item`)
- Citations (`> citation` → `<quote>citation</quote>`)
- Notes de bas de page (`Texte[^1]` + `[^1]: note` → `Texte[[note]]`)

### Interface utilisateur

- Interface en deux colonnes : Markdown à gauche, SPIP à droite
- Conversion en temps réel avec debounce de 50ms
- Bouton de copie vers le presse-papier avec retour visuel
- Bouton d'effacement du texte
- Compteur de caractères en temps réel (limite : 100 000 caractères)
- Mode sombre activé par défaut avec bascule
- Sauvegarde automatique dans le localStorage du navigateur
- Modale d'aide listant toutes les conversions supportées
- Gestion élégante de l'expiration de session (erreur 419)

### Accessibilité

- Support complet ARIA (labels, rôles, descriptions)
- Navigation au clavier sur tous les éléments interactifs
- Compatibilité lecteurs d'écran
- Score Lighthouse Accessibilité : 100/100

### Sécurité et performance

- Rate limiting : 300 requêtes/minute par IP
- Headers de sécurité complets (CSP, X-Frame-Options, etc.)
- Protection anti-spam de l'email de contact
- Aucune donnée stockée côté serveur (respect RGPD)
- Score Lighthouse Performance : 100/100

### SEO

- Balises meta complètes (Open Graph, Twitter Card)
- Données structurées Schema.org (WebApplication)
- URL canonique dynamique

### Pages

- Page principale de conversion
- Mentions légales

### Stack technique

- Laravel 12
- Livewire 3
- Alpine.js
- Tailwind CSS 4
- PHP 8.3
