@props(['urlFr', 'urlEn'])
@php
    $isEn = app()->getLocale() === 'en';
    $altUrl = $isEn ? $urlFr : $urlEn;
@endphp
<a href="{{ $altUrl }}"
   class="group inline-flex items-center gap-1.5 text-xs font-semibold tracking-wide px-2.5 py-1 rounded-full border border-gray-300 dark:border-slate-600 bg-white/60 dark:bg-slate-800/60 text-gray-700 dark:text-slate-300 hover:bg-emerald-500 hover:border-emerald-500 hover:text-white dark:hover:bg-emerald-500 dark:hover:border-emerald-500 dark:hover:text-white hover:shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800"
   aria-label="{{ __('messages.switcher.aria') }}"
   hreflang="{{ $isEn ? 'fr' : 'en' }}">
    <x-icon.globe class="w-3.5 h-3.5 opacity-70 group-hover:opacity-100 transition-opacity" />
    {{ $isEn ? 'FR' : 'EN' }}
</a>
