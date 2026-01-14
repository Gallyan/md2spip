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
- Code inline et blocs de code
- Liens (`[texte](url)` → `[texte->url]`)
- Listes à puces (`- item` → `-* item`)
- Citations (`> citation` → `<quote>citation</quote>`)
- Notes de bas de page (`Texte[^1]` + `[^1]: note` → `Texte[[note]]`)

### Interface utilisateur

- Interface en deux colonnes avec conversion en temps réel
- Copie en un clic vers le presse-papier
- Compteur de caractères
- Mode sombre par défaut
- Sauvegarde automatique dans le navigateur
- Modale d'aide

### Accessibilité

- Support ARIA complet
- Navigation au clavier
- Compatibilité lecteurs d'écran

### SEO

- Données structurées Schema.org

### Vie privée

- Aucune donnée stockée côté serveur
