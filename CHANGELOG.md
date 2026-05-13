# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.1.0] - 2026-05-13

### Added

- Public usage statistics page (`/stats`) with KPI cards and 30-day chart (visits / conversions / copies / characters)
- `stats:seed` Artisan command to populate fake statistics for local development
- English version of the site under `/en` with language switcher, `hreflang` alternates and bilingual JSON-LD
- Enriched Schema.org `@graph`: `WebSite`, `Person`, `WebApplication`, `FAQPage`, `HowTo`, `SoftwareSourceCode`
- Dynamic `/sitemap.xml` with XSL stylesheet for human reading
- Dynamic `/robots.txt` declaring sitemap and noindex paths
- Open Graph image and Twitter Card (`summary_large_image`)
- Continuous Deployment workflow (`.github/workflows/pipeline.yml`) with SSH push to the production server after green tests
- Pre-commit Git hook running Pint and PHPStan on staged PHP files
- Skip link for keyboard navigation and focus management on the help modal
- Server-side rate limiting and validation on copy/conversion tracking endpoints
- Custom scrollbar styling supporting Firefox and Chromium-based browsers
- `prefers-reduced-motion` CSS support
- `LEGAL_*` environment variables driving the legal notice content; sections hide automatically when their variables are empty
- English `README.md` and `CHANGELOG.md`

### Changed

- Upgraded the stack to Laravel 13, Livewire 4, PHPUnit 13, Vite 8, TailwindCSS 4
- Minimum PHP version bumped to 8.4
- Legal notice page is now always present (versioned Blade), driven by `config/legal.php`; the `EXAMPLE` template variant has been removed
- Contact email redirect moved to `/contact` (formerly `/contact-email`)
- All SVG icons extracted into reusable Blade components (`x-icon.*`)
- Stats storage refactored to use Laravel's `Storage` facade on a dedicated `stats` disk, with `flock` exclusive locking to prevent concurrent write loss
- Footer made responsive on mobile (stacks vertically below `sm` breakpoint)
- Language switcher restyled with a globe icon and emerald hover state
- Character count on the stats dashboard now abbreviated with k/M/G suffixes
- Code comments, PHPDoc and tests are now in English

### Security

- Production cookies enforced as `secure`, `httponly`, `samesite=strict`
- Anti-cache headers on the contact redirect (`Cache-Control: no-store`, `X-Robots-Tag: noindex`)
- Removed `php artisan optimize` from the deploy script to avoid config cache desync (caches are rebuilt on first request under the correct PHP-FPM user)

### Fixed

- Insufficient contrast on the empty-state placeholder of the SPIP output
- Stats KPI cards layout on tablet (now aligned from `md` breakpoint)
- JSON-LD validation warnings (`codeRepository`, `programmingLanguage`, invalid `ConvertAction` type)

## [1.0.0] - 2026-01-14

First stable release of the Markdown to SPIP converter.

### Markdown to SPIP conversion

- H1 headings (`# Title` → `{{{Title}}}`)
- H2-H6 subheadings (`## Subtitle` → `{{Subtitle}}`)
- Bold (`**bold**` → `{{bold}}`)
- Italic (`*italic*` → `{italic}`)
- Combined bold and italic (`***text***` → `{{{text}}}`)
- Strikethrough (`~~strike~~` → `<del>strike</del>`)
- Inline code and code blocks
- Links (`[text](url)` → `[text->url]`)
- Bullet lists (`- item` → `-* item`)
- Blockquotes (`> quote` → `<quote>quote</quote>`)
- Footnotes (`Text[^1]` + `[^1]: note` → `Text[[note]]`)

### User interface

- Two-column interface with real-time conversion
- One-click copy to clipboard
- Character counter
- Dark mode by default
- Automatic saving in the browser
- Help modal

### Accessibility

- Full ARIA support
- Keyboard navigation
- Screen reader compatibility

### SEO

- Schema.org structured data

### Privacy

- No data stored server-side
