# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

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
