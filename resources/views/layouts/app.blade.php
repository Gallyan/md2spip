<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markdown to SPIP - Convertisseur en ligne gratuit</title>
    <meta name="description" content="Convertissez instantanément votre Markdown en syntaxe SPIP. Outil en ligne gratuit, sans inscription, respectueux de votre vie privée.">
    <meta name="keywords" content="markdown, spip, convertisseur, conversion, en ligne, gratuit">
    <meta name="author" content="md2spip">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://md2spip.orsal.net/">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Markdown to SPIP - Convertisseur en ligne">
    <meta property="og:description" content="Convertissez instantanément votre Markdown en syntaxe SPIP. Gratuit et respectueux de votre vie privée.">
    <meta property="og:url" content="https://md2spip.orsal.net/">
    <meta property="og:locale" content="fr_FR">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Markdown to SPIP">
    <meta name="twitter:description" content="Convertisseur Markdown vers SPIP en ligne, gratuit et sans tracking.">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    {{-- Theme color --}}
    <meta name="theme-color" content="#1e293b">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 min-h-screen flex flex-col">
    {{ $slot }}
</body>
</html>
