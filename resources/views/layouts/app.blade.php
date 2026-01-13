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
    <link rel="canonical" href="https://markdown2spip.orsal.fr/">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Markdown to SPIP - Convertisseur en ligne">
    <meta property="og:description" content="Convertissez instantanément votre Markdown en syntaxe SPIP. Gratuit et respectueux de votre vie privée.">
    <meta property="og:url" content="https://markdown2spip.orsal.fr/">
    <meta property="og:locale" content="fr_FR">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Markdown to SPIP">
    <meta name="twitter:description" content="Convertisseur Markdown vers SPIP en ligne, gratuit et sans tracking.">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    {{-- Theme color --}}
    <meta name="theme-color" content="#1e293b">

    {{-- Schema.org JSON-LD --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "Markdown to SPIP",
        "alternateName": "markdown2spip",
        "description": "Convertisseur en ligne gratuit pour transformer du Markdown en syntaxe SPIP. Instantané, sans inscription, respectueux de la vie privée.",
        "url": "https://markdown2spip.orsal.fr/",
        "applicationCategory": "UtilitiesApplication",
        "operatingSystem": "Any",
        "browserRequirements": "Requires JavaScript",
        "permissions": "none",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "EUR"
        },
        "author": {
            "@type": "Person",
            "name": "Guillaume Orsal",
            "url": "https://www.orsal.fr"
        },
        "publisher": {
            "@type": "Person",
            "name": "Guillaume Orsal",
            "url": "https://www.orsal.fr"
        },
        "inLanguage": "fr",
        "isAccessibleForFree": true,
        "license": "https://github.com/Gallyan/md2spip/blob/main/LICENSE"
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
