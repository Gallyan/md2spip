@php
    $isEn = \App\Support\LocaleUrls::isEnglish();
    $guideUrl = $isEn ? '/en/guide' : '/guide';
    $statsUrl = $isEn ? '/en/stats' : '/stats';
    $legalUrl = $isEn ? '/en/legal' : '/mentions-legales';
@endphp
<footer class="bg-gray-100 dark:bg-slate-800 border-t border-gray-300 dark:border-slate-700 px-4 sm:px-6 py-2 flex flex-col sm:flex-row items-center sm:justify-between gap-1 sm:gap-3 text-xs text-gray-600 dark:text-slate-400 transition-colors">
    <div class="flex flex-col sm:flex-row sm:flex-wrap items-center justify-center gap-x-1.5 gap-y-0.5 text-center">
        <span class="inline-flex items-center gap-1.5">
            {{ __('messages.footer.open_source') }} <a href="https://github.com/Gallyan/md2spip/blob/main/LICENSE" target="_blank" rel="noopener" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors underline">AGPL-3.0</a>
            <span aria-hidden="true">•</span>
            <a href="https://github.com/Gallyan/md2spip" target="_blank" rel="noopener" aria-label="{{ __('messages.footer.github_aria') }}" class="inline-flex hover:text-gray-900 dark:hover:text-slate-300 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded">
                <x-icon.github class="w-3.5 h-3.5" />
            </a>
        </span>
        <span aria-hidden="true" class="hidden sm:inline">•</span>
        <span class="inline-flex items-center gap-1.5">
            {{ __('messages.footer.created_by') }} <a href="https://www.orsal.fr" target="_blank" rel="noopener" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors underline">Guillaume Orsal</a> {{ __('messages.footer.year_suffix') }}
        </span>
    </div>
    <span class="inline-flex items-center gap-1.5 shrink-0">
        <a href="{{ $guideUrl }}" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors">{{ __('messages.footer.guide') }}</a>
        <span aria-hidden="true">•</span>
        <a href="{{ $guideUrl }}#api" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors">{{ __('messages.footer.api') }}</a>
        <span aria-hidden="true">•</span>
        <a href="{{ $statsUrl }}" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors">{{ __('messages.footer.stats') }}</a>
        <span aria-hidden="true">•</span>
        <a href="{{ $legalUrl }}" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors">{{ __('messages.footer.legal') }}</a>
    </span>
</footer>
