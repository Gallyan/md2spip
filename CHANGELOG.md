# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.3.0] - 2026-09-15

### Added

- Console banner with a link to the author's site
- The draft kept in the browser is dropped 7 days after the last edit

### Changed

- The draft restored from localStorage is converted immediately, instead of waiting for the next keystroke
- Conversion rules declared in a single table, each preceded by a comment showing the Markdown input and the SPIP output
- Rate-limit checks factored into a single helper in the Livewire component; the help modal example words now come from the translation files
- Stack updated to Laravel 13.31, Livewire 4.4.5, PHPUnit 13.3.3, Vite 8.3, TailwindCSS 4.3
- `humans.txt` points to the current domain and lists the current stack

### Removed

- `axios` and `alpinejs` npm packages, never imported by the front-end (Alpine ships with Livewire); the JavaScript bundle drops from 52 KB to 2 KB
- Sample application key in `.env.example`

## [1.2.0] - 2026-08-20

### Added

- System theme option following the OS preference, now the default (light and dark remain selectable)
- Favicon

### Changed

- CI/CD split into two workflows: `ci.yml` runs the test suite, `cd.yml` deploys after a green run on `main`
- Numbers are formatted by `Number::format()` following the application locale, replacing hand-written
  separators; French thousands now use a narrow no-break space (U+202F)
- Stats reading and writing extracted into `App\Support\Stats`, giving a single typed access point and
  removing the decoding logic duplicated across the controller, the command and the Livewire component
- Static analysis raised from PHPStan level 8 to level 10
- CI now tests against both PHP 8.4 and 8.5
- `.env.production` renamed to `.env.production.example`
- Stack updated to Laravel 13.26, Livewire 4.4, Guzzle 8, PHPUnit 13.3, Vite 8.2, TailwindCSS 4.3
- `actions/checkout` and `actions/setup-node` bumped to v7

### Removed

- Dependabot configuration and its open pull requests; dependencies are updated manually

### Fixed

- White flash when navigating between pages
- Blank values no longer break the statistics aggregation
- The 30-day grid is only drawn once data exists
- All PHP-FPM pools are restarted after deployment, so OPcache no longer serves stale code
- Outdated claims in `README.md` and `public/llms.txt`: the deployment workflow name, the default theme,
  the PHPStan level, and the privacy wording, which stated that no data was stored server-side while
  anonymous aggregate counters are

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

[1.2.0]: https://github.com/Gallyan/md2spip/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/Gallyan/md2spip/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/Gallyan/md2spip/releases/tag/v1.0.0
