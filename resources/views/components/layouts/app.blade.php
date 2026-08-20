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
        (function () {
            const t = localStorage.getItem('md2spip-theme') || 'system';
            const dark = t === 'dark' || (t === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('messages.meta.title') }}</title>
    <meta name="robots" content="{{ $robots }}">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
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
        theme: localStorage.getItem('md2spip-theme') || 'system',
        init() {
            this.apply();
            window.matchMedia('(prefers-color-scheme: dark)')
                .addEventListener('change', () => { if (this.theme === 'system') this.apply(); });
        },
        cycleTheme() {
            this.theme = this.theme === 'system' ? 'dark' : this.theme === 'dark' ? 'light' : 'system';
            if (this.theme === 'system') {
                localStorage.removeItem('md2spip-theme');
            } else {
                localStorage.setItem('md2spip-theme', this.theme);
            }
            this.apply();
        },
        apply() {
            const dark = this.theme === 'dark'
                || (this.theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        }
    }">
    {{ $slot }}
    @livewireScripts
</body>
</html>
