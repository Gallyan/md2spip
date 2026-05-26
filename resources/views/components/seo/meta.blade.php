@php
    $isEn = \App\Support\LocaleUrls::isEnglish();
    $currentUrl = \App\Support\LocaleUrls::current();
    $urlFr = \App\Support\LocaleUrls::alternateFr();
    $urlEn = \App\Support\LocaleUrls::alternateEn();
@endphp
<meta name="description" content="{{ __('messages.meta.description') }}">
<meta name="keywords" content="{{ __('messages.meta.keywords') }}">
<meta name="author" content="Guillaume Orsal">
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
