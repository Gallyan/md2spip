@props(['urlFr', 'urlEn'])
@php
    $isEn = app()->getLocale() === 'en';
    $altUrl = $isEn ? $urlFr : $urlEn;
@endphp
<a href="{{ $altUrl }}"
   class="text-xs font-semibold uppercase tracking-wide px-2 py-1 rounded bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800"
   aria-label="{{ __('messages.switcher.aria') }}"
   hreflang="{{ $isEn ? 'fr' : 'en' }}">{{ $isEn ? 'FR' : 'EN' }}</a>
