@php
    $locale = app()->getLocale();
    $isEn = $locale === 'en';
    $urlFr = url(request()->path() === '/' ? '/' : '/'.ltrim(preg_replace('#^/?en/?#', '', request()->path()) ?: '', '/'));
    $urlEn = $isEn ? url(request()->path()) : url('/en'.(request()->path() === '/' ? '' : '/'.preg_replace(['#^mentions-legales$#', '#^stats$#', '#^contact$#'], ['legal', 'stats', 'contact'], request()->path())));
    $currentUrl = url(request()->path() === '/' ? '/' : '/'.request()->path());
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        if (localStorage.getItem('md2spip-theme') !== 'light') {
            document.documentElement.classList.add('dark');
        }
    </script>
    <title>{{ __('messages.meta.title') }}</title>
    <meta name="description" content="{{ __('messages.meta.description') }}">
    <meta name="keywords" content="{{ __('messages.meta.keywords') }}">
    <meta name="author" content="Guillaume Orsal">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $currentUrl }}">

    {{-- Hreflang alternates --}}
    <link rel="alternate" hreflang="fr" href="{{ $urlFr }}">
    <link rel="alternate" hreflang="en" href="{{ $urlEn }}">
    <link rel="alternate" hreflang="x-default" href="{{ $urlFr }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ __('messages.meta.og_title') }}">
    <meta property="og:description" content="{{ __('messages.meta.og_description') }}">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:locale" content="{{ __('messages.meta.og_locale') }}">
    <meta property="og:locale:alternate" content="{{ $isEn ? 'fr_FR' : 'en_GB' }}">
    <meta property="og:image" content="{{ asset('og-image.png') }}">
    <meta property="og:image:width" content="1320">
    <meta property="og:image:height" content="755">
    <meta property="og:image:alt" content="{{ __('messages.meta.image_alt') }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Markdown to SPIP">
    <meta name="twitter:description" content="{{ __('messages.meta.twitter_description') }}">
    <meta name="twitter:image" content="{{ asset('og-image.png') }}">
    <meta name="twitter:image:alt" content="{{ __('messages.meta.image_alt') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    {{-- Theme color --}}
    <meta name="theme-color" content="#1e293b">

    @php
        $faqs = $isEn ? [
            ['q' => 'Is my text stored on the server?', 'a' => 'No. The text is sent to the server only to perform the real-time conversion, but it is never stored. No trace of conversions is kept.'],
            ['q' => 'Is the tool free?', 'a' => 'Yes. Markdown to SPIP is completely free, no signup, no advertising. The project is open source under the GNU GPL-3.0 license.'],
            ['q' => 'What is the maximum length of text to convert?', 'a' => '100,000 characters per conversion, roughly 15,000 words or 30 pages.'],
            ['q' => 'Which Markdown syntaxes are supported?', 'a' => 'Headings, bold, italic, bold+italic, strikethrough, inline code and code blocks, links, bullet lists, blockquotes and footnotes. The conversion produces the equivalent SPIP syntax.'],
            ['q' => 'Can I use Markdown to SPIP offline?', 'a' => 'Yes. As the project is open source, you can install it locally on your machine. The source code is available on GitHub.'],
            ['q' => 'What is SPIP?', 'a' => 'SPIP (Système de Publication pour Internet Partagé) is a free French CMS used by many institutional and non-profit websites. It uses its own markup syntax, distinct from Markdown.'],
        ] : [
            ['q' => 'Mes textes sont-ils stockés sur le serveur ?', 'a' => "Non. Le texte est transmis au serveur uniquement pour effectuer la conversion en temps réel, mais il n'est jamais stocké. Aucune trace des conversions n'est conservée."],
            ['q' => "L'outil est-il gratuit ?", 'a' => "Oui. Markdown to SPIP est totalement gratuit, sans inscription et sans publicité. Le projet est open source sous licence GNU GPL-3.0."],
            ['q' => "Quelle est la longueur maximale d'un texte à convertir ?", 'a' => "100 000 caractères par conversion, soit l'équivalent d'environ 15 000 mots ou 30 pages."],
            ['q' => 'Quelles syntaxes Markdown sont supportées ?', 'a' => 'Titres, gras, italique, gras+italique, texte barré, code inline et blocs de code, liens, listes à puces, citations et notes de bas de page. La conversion produit la syntaxe SPIP équivalente.'],
            ['q' => 'Puis-je utiliser Markdown to SPIP hors ligne ?', 'a' => "Oui. Le projet étant open source, vous pouvez l'installer localement sur votre machine. Le code source est disponible sur GitHub."],
            ['q' => "Qu'est-ce que SPIP ?", 'a' => "SPIP (Système de Publication pour Internet Partagé) est un CMS libre français utilisé par de nombreux sites institutionnels et associatifs. Il utilise une syntaxe de mise en forme spécifique, différente de celle de Markdown."],
        ];
        $howToSteps = $isEn ? [
            ['name' => 'Paste the Markdown', 'text' => 'Paste or type your Markdown text in the left-hand pane.'],
            ['name' => 'See the SPIP result', 'text' => 'The SPIP conversion appears automatically and in real time on the right.'],
            ['name' => 'Copy the result', 'text' => 'Click the "Copy" button to grab the SPIP text into your clipboard.'],
        ] : [
            ['name' => 'Coller le Markdown', 'text' => 'Collez ou tapez votre texte Markdown dans la zone de gauche.'],
            ['name' => 'Voir le résultat SPIP', 'text' => "La conversion en syntaxe SPIP s'affiche automatiquement et en temps réel à droite."],
            ['name' => 'Copier le résultat', 'text' => 'Cliquez sur le bouton « Copier » pour récupérer le texte SPIP dans votre presse-papier.'],
        ];
        $features = $isEn ? [
            'Real-time Markdown to SPIP conversion',
            'Split-view interface (input / output)',
            'Local text persistence (localStorage)',
            'Dark mode by default',
            'No personal data collected',
            'Open source GPL-3.0',
        ] : [
            'Conversion Markdown vers SPIP en temps réel',
            'Interface split-view (saisie / résultat)',
            'Persistance locale du texte (localStorage)',
            'Mode sombre par défaut',
            'Aucune donnée personnelle collectée',
            'Open source GPL-3.0',
        ];
        $howToName = $isEn ? 'How to convert Markdown to SPIP' : 'Comment convertir du Markdown vers SPIP';
        $howToDescription = $isEn
            ? 'Convert your Markdown text to SPIP syntax instantly in a few steps.'
            : 'Convertissez instantanément votre texte Markdown vers la syntaxe SPIP en quelques étapes.';
        $appDescription = $isEn
            ? 'Free online converter that turns Markdown into SPIP syntax. Instant, no signup, privacy-friendly.'
            : 'Convertisseur en ligne gratuit pour transformer du Markdown en syntaxe SPIP. Instantané, sans inscription, respectueux de la vie privée.';
        $appKeywords = $isEn
            ? 'markdown, spip, converter, conversion, SPIP syntax, formatting'
            : 'markdown, spip, convertisseur, conversion, syntaxe SPIP, mise en forme';
    @endphp

    {{-- Schema.org JSON-LD @graph --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "WebSite",
                "@@id": "{{ url('/') }}/#website",
                "name": "{{ config('app.name') }}",
                "url": "{{ url('/') }}",
                "description": "{{ __('messages.meta.description_short') }}",
                "inLanguage": ["fr", "en"],
                "publisher": { "@@id": "{{ url('/') }}/#person" }
            },
            {
                "@@type": "Person",
                "@@id": "{{ url('/') }}/#person",
                "name": "{{ config('legal.editor_name') }}",
                "url": "{{ config('legal.social.website', 'https://www.orsal.fr') }}",
                "jobTitle": "Software Engineer",
                "knowsAbout": ["Laravel development", "web development", "open source", "Markdown", "SPIP CMS"]@if(collect(config('legal.social'))->filter()->isNotEmpty())
                ,"sameAs": {!! json_encode(collect(config('legal.social'))->filter()->values()) !!}
                @endif
            },
            {
                "@@type": ["WebApplication", "SoftwareApplication"],
                "@@id": "{{ url('/') }}/#application",
                "name": "{{ config('app.name') }}",
                "alternateName": "markdown2spip",
                "description": "{{ $appDescription }}",
                "url": "{{ $currentUrl }}",
                "applicationCategory": "UtilitiesApplication",
                "applicationSubCategory": "Text conversion tool",
                "operatingSystem": "Any",
                "browserRequirements": "Requires JavaScript",
                "inLanguage": "{{ $locale }}",
                "image": "{{ asset('og-image.png') }}",
                "screenshot": {
                    "@@type": "ImageObject",
                    "url": "{{ asset('og-image.png') }}",
                    "width": 1320,
                    "height": 755,
                    "caption": "{{ __('messages.meta.image_alt') }}"
                },
                "keywords": "{{ $appKeywords }}",
                "dateCreated": "2026-01-01",
                "datePublished": "2026-01-15",
                "isAccessibleForFree": true,
                "license": "https://www.gnu.org/licenses/gpl-3.0",
                "isBasedOn": {
                    "@@type": "SoftwareSourceCode",
                    "codeRepository": "https://github.com/Gallyan/md2spip",
                    "programmingLanguage": ["PHP", "JavaScript"],
                    "license": "https://www.gnu.org/licenses/gpl-3.0"
                },
                "offers": {
                    "@@type": "Offer",
                    "price": "0",
                    "priceCurrency": "EUR"
                },
                "featureList": {!! json_encode($features, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
                "potentialAction": {
                    "@@type": "CreateAction",
                    "target": {
                        "@@type": "EntryPoint",
                        "urlTemplate": "{{ url('/') }}",
                        "actionPlatform": [
                            "http://schema.org/DesktopWebPlatform",
                            "http://schema.org/MobileWebPlatform"
                        ]
                    }
                },
                "author": { "@@id": "{{ url('/') }}/#person" },
                "creator": { "@@id": "{{ url('/') }}/#person" },
                "publisher": { "@@id": "{{ url('/') }}/#person" }
            },
            {
                "@@type": "FAQPage",
                "@@id": "{{ url('/') }}/#faq",
                "inLanguage": "{{ $locale }}",
                "mainEntity": [
                    @foreach ($faqs as $i => $faq)
                    {
                        "@@type": "Question",
                        "name": {!! json_encode($faq['q'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": {!! json_encode($faq['a'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}
                        }
                    }@if(! $loop->last),@endif
                    @endforeach
                ]
            },
            {
                "@@type": "HowTo",
                "@@id": "{{ url('/') }}/#howto",
                "inLanguage": "{{ $locale }}",
                "name": {!! json_encode($howToName, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
                "description": {!! json_encode($howToDescription, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
                "totalTime": "PT10S",
                "step": [
                    @foreach ($howToSteps as $i => $step)
                    {
                        "@@type": "HowToStep",
                        "position": {{ $i + 1 }},
                        "name": {!! json_encode($step['name'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
                        "text": {!! json_encode($step['text'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}
                    }@if(! $loop->last),@endif
                    @endforeach
                ]
            }
        ]
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-slate-900 min-h-screen flex flex-col transition-colors">
    <a href="#markdown-input" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:px-4 focus:py-2 focus:bg-emerald-500 focus:text-white focus:rounded">{{ __('messages.skip_to_content') }}</a>

    {{-- Session expired banner --}}
    <div
        x-data="{ show: false }"
        x-init="window.addEventListener('session-expired', () => show = true)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-full"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-full"
        x-cloak
        class="fixed top-0 inset-x-0 z-50"
        role="alert"
        aria-live="assertive"
    >
        <div class="bg-amber-50 dark:bg-amber-900/90 border-b border-amber-200 dark:border-amber-700 px-4 py-3 shadow-lg">
            <div class="max-w-4xl mx-auto flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <x-icon.exclamation-circle class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0" />
                    <p class="text-sm text-amber-800 dark:text-amber-100">
                        <strong>{{ __('messages.session.expired_strong') }}</strong> — {{ __('messages.session.expired_text') }}
                    </p>
                </div>
                <button
                    @click="window.location.reload()"
                    class="shrink-0 px-3 py-1.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-600 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-amber-900"
                >
                    {{ __('messages.session.reload') }}
                </button>
            </div>
        </div>
    </div>

    {{ $slot }}
</body>
</html>
