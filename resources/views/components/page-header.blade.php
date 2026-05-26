@props(['back', 'title', 'margin' => 'mb-12'])
<header class="{{ $margin }}">
    <div class="flex items-center justify-between mb-8">
        <a href="{{ \App\Support\LocaleUrls::isEnglish() ? '/en' : '/' }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-900 dark:text-white rounded-lg transition-colors border border-gray-300 dark:border-slate-700">
            <x-icon.arrow-left />
            <span class="text-sm font-medium">{{ $back }}</span>
        </a>

        <div class="flex items-center gap-3">
            <x-language-switcher />
            <x-theme-toggle offset="dark:focus:ring-offset-slate-900" />
        </div>
    </div>
    <h1 class="text-5xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
</header>
