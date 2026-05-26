@props([
    'title' => null,
    'robots' => 'index, follow',
])
@php
    $locale = \App\Support\LocaleUrls::locale();
    $isHome = request()->routeIs('home', 'en.home');
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" class="dark">
<head>
    <script>
        if (localStorage.getItem('md2spip-theme') === 'light') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('messages.meta.title') }}</title>
    <meta name="robots" content="{{ $robots }}">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <meta name="theme-color" content="#1e293b">

    @if ($isHome)
        <x-seo.meta />
        <x-seo.structured-data />
    @endif

    {{ $head ?? '' }}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 dark:bg-slate-900 min-h-screen flex flex-col text-gray-900 dark:text-slate-100 transition-colors"
    x-data="{
        darkMode: localStorage.getItem('md2spip-theme') !== 'light',
        init() { this.updateTheme(); },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            this.updateTheme();
        },
        updateTheme() {
            localStorage.setItem('md2spip-theme', this.darkMode ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.darkMode);
        }
    }">
    {{ $slot }}
    @livewireScripts
</body>
</html>
