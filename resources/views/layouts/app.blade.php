<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markdown to SPIP - Convertisseur en ligne gratuit</title>
    <meta name="description" content="Convertissez instantanément votre Markdown en syntaxe SPIP. Outil en ligne gratuit, sans inscription, respectueux de votre vie privée.">
    <meta name="keywords" content="markdown, spip, convertisseur, conversion, en ligne, gratuit">
    <meta name="author" content="Guillaume Orsal">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Markdown to SPIP - Convertisseur en ligne">
    <meta property="og:description" content="Convertissez instantanément votre Markdown en syntaxe SPIP. Gratuit et respectueux de votre vie privée.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:image" content="{{ asset('og-image.png') }}">
    <meta property="og:image:width" content="1320">
    <meta property="og:image:height" content="755">
    <meta property="og:image:alt" content="Capture de l'interface du convertisseur Markdown vers SPIP">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Markdown to SPIP">
    <meta name="twitter:description" content="Convertisseur Markdown vers SPIP en ligne, gratuit et sans tracking.">
    <meta name="twitter:image" content="{{ asset('og-image.png') }}">
    <meta name="twitter:image:alt" content="Capture de l'interface du convertisseur Markdown vers SPIP">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    {{-- Theme color --}}
    <meta name="theme-color" content="#1e293b">

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
                "description": "Convertisseur en ligne Markdown vers syntaxe SPIP, en temps réel.",
                "inLanguage": "fr",
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
                "description": "Convertisseur en ligne gratuit pour transformer du Markdown en syntaxe SPIP. Instantané, sans inscription, respectueux de la vie privée.",
                "url": "{{ url('/') }}",
                "applicationCategory": "UtilitiesApplication",
                "applicationSubCategory": "Text conversion tool",
                "operatingSystem": "Any",
                "browserRequirements": "Requires JavaScript",
                "inLanguage": "fr",
                "image": "{{ asset('og-image.png') }}",
                "screenshot": {
                    "@@type": "ImageObject",
                    "url": "{{ asset('og-image.png') }}",
                    "width": 1320,
                    "height": 755,
                    "caption": "Capture de l'interface du convertisseur Markdown vers SPIP"
                },
                "keywords": "markdown, spip, convertisseur, conversion, syntaxe SPIP, mise en forme",
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
                "featureList": [
                    "Conversion Markdown vers SPIP en temps réel",
                    "Interface split-view (saisie / résultat)",
                    "Persistance locale du texte (localStorage)",
                    "Mode sombre par défaut",
                    "Aucune donnée personnelle collectée",
                    "Open source GPL-3.0"
                ],
                "potentialAction": {
                    "@@type": "ConvertAction",
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
                "mainEntity": [
                    {
                        "@@type": "Question",
                        "name": "Mes textes sont-ils stockés sur le serveur ?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "Non. Le texte est transmis au serveur uniquement pour effectuer la conversion en temps réel, mais il n'est jamais stocké. Aucune trace des conversions n'est conservée."
                        }
                    },
                    {
                        "@@type": "Question",
                        "name": "L'outil est-il gratuit ?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "Oui. Markdown to SPIP est totalement gratuit, sans inscription et sans publicité. Le projet est open source sous licence GNU GPL-3.0."
                        }
                    },
                    {
                        "@@type": "Question",
                        "name": "Quelle est la longueur maximale d'un texte à convertir ?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "100 000 caractères par conversion, soit l'équivalent d'environ 15 000 mots ou 30 pages."
                        }
                    },
                    {
                        "@@type": "Question",
                        "name": "Quelles syntaxes Markdown sont supportées ?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "Titres, gras, italique, gras+italique, texte barré, code inline et blocs de code, liens, listes à puces, citations et notes de bas de page. La conversion produit la syntaxe SPIP équivalente."
                        }
                    },
                    {
                        "@@type": "Question",
                        "name": "Puis-je utiliser Markdown to SPIP hors ligne ?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "Oui. Le projet étant open source, vous pouvez l'installer localement sur votre machine. Le code source est disponible sur GitHub."
                        }
                    },
                    {
                        "@@type": "Question",
                        "name": "Qu'est-ce que SPIP ?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "SPIP (Système de Publication pour Internet Partagé) est un CMS libre français utilisé par de nombreux sites institutionnels et associatifs. Il utilise une syntaxe de mise en forme spécifique, différente de celle de Markdown."
                        }
                    }
                ]
            },
            {
                "@@type": "HowTo",
                "@@id": "{{ url('/') }}/#howto",
                "name": "Comment convertir du Markdown vers SPIP",
                "description": "Convertissez instantanément votre texte Markdown vers la syntaxe SPIP en quelques étapes.",
                "totalTime": "PT10S",
                "step": [
                    {
                        "@@type": "HowToStep",
                        "position": 1,
                        "name": "Coller le Markdown",
                        "text": "Collez ou tapez votre texte Markdown dans la zone de gauche."
                    },
                    {
                        "@@type": "HowToStep",
                        "position": 2,
                        "name": "Voir le résultat SPIP",
                        "text": "La conversion en syntaxe SPIP s'affiche automatiquement et en temps réel à droite."
                    },
                    {
                        "@@type": "HowToStep",
                        "position": 3,
                        "name": "Copier le résultat",
                        "text": "Cliquez sur le bouton « Copier » pour récupérer le texte SPIP dans votre presse-papier."
                    }
                ]
            }
        ]
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-slate-900 min-h-screen flex flex-col transition-colors">
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <p class="text-sm text-amber-800 dark:text-amber-100">
                        <strong>Session expirée</strong> — Rechargez la page pour continuer. Votre texte est sauvegardé.
                    </p>
                </div>
                <button
                    @click="window.location.reload()"
                    class="shrink-0 px-3 py-1.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-600 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-amber-900"
                >
                    Recharger
                </button>
            </div>
        </div>
    </div>

    {{ $slot }}
</body>
</html>
