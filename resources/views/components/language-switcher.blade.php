@props(['urlFr', 'urlEn'])
@php
    $isEn = app()->getLocale() === 'en';
    $active = 'bg-emerald-500 text-white shadow-sm';
    $inactive = 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white';
@endphp
<div role="group" aria-label="{{ __('messages.switcher.aria') }}" class="inline-flex items-center bg-gray-200 dark:bg-slate-700 rounded-full p-0.5 text-xs font-semibold">
    <a href="{{ $urlFr }}"
       class="px-2.5 py-1 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 {{ $isEn ? $inactive : $active }}"
       @if (! $isEn) aria-current="page" @endif
       hreflang="fr"
       lang="fr"
       title="Français">FR</a>
    <a href="{{ $urlEn }}"
       class="px-2.5 py-1 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 {{ $isEn ? $active : $inactive }}"
       @if ($isEn) aria-current="page" @endif
       hreflang="en"
       lang="en"
       title="English">EN</a>
</div>
