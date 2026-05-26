@php
    $isEn = \App\Support\LocaleUrls::isEnglish();
    $statsUrl = $isEn ? '/en/stats' : '/stats';
    $legalUrl = $isEn ? '/en/legal' : '/mentions-legales';
@endphp
<div class="flex flex-col h-screen"
    x-data="{
        init() {
            const saved = localStorage.getItem('md2spip-markdown');
            if (saved && saved !== '') {
                $wire.markdown = saved;
            }
        }
    }"
    x-effect="
        localStorage.setItem('md2spip-markdown', $wire.markdown || '');
        if ($wire.markdown) {
            if (!localStorage.getItem('md2spip-converted')) {
                localStorage.setItem('md2spip-converted', '1');
                $wire.trackConversion();
            }
        } else {
            localStorage.removeItem('md2spip-converted');
        }
    ">
    <a href="#markdown-input" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:px-4 focus:py-2 focus:bg-emerald-500 focus:text-white focus:rounded">{{ __('messages.skip_to_content') }}</a>

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
                    <x-icon.exclamation-circle class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0" />
                    <p class="text-sm text-amber-800 dark:text-amber-100">
                        <strong>{{ __('messages.session.expired_strong') }}</strong> — {{ __('messages.session.expired_text') }}
                    </p>
                </div>
                <button
                    @click="window.location.reload()"
                    class="shrink-0 px-3 py-1.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-600 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-amber-900"
                >
                    {{ __('messages.session.reload') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <header class="bg-gray-100 dark:bg-slate-800 border-b border-gray-300 dark:border-slate-700 px-6 py-3 flex items-center justify-between transition-colors">
        <div class="flex items-center gap-3">
            <div class="flex flex-col">
                <div class="flex items-center gap-3">
                    <x-logo />
                    <div>
                        <h1 class="text-gray-900 dark:text-white font-semibold text-lg leading-tight">Markdown to SPIP</h1>
                        <h2 class="hidden md:block text-gray-600 dark:text-slate-300 text-xs font-normal">{{ __('messages.home.subtitle') }}</h2>
                    </div>
                </div>
            </div>

            <x-help-modal />
        </div>

        <div class="flex items-center gap-3">
            <x-language-switcher />
            <x-theme-toggle />
        </div>
    </header>

    {{-- Main content --}}
    <main class="flex-1 grid grid-cols-1 md:grid-cols-2 min-h-0">
        {{-- Markdown input --}}
        <div class="flex flex-col border-r border-gray-300 dark:border-slate-700 min-h-0">
            <div class="bg-gray-100 dark:bg-slate-800 px-4 py-2 border-b border-gray-300 dark:border-slate-700 flex items-center justify-between transition-colors">
                <label for="markdown-input" id="markdown-label" class="text-gray-700 dark:text-slate-300 text-sm font-semibold uppercase tracking-wide">{{ __('messages.home.markdown_label') }}</label>

                <div class="flex items-center gap-3">
                    {{-- Character counter --}}
                    <span
                        class="text-xs font-mono"
                        :class="($wire.markdown || '').length > {{ \App\Livewire\MarkdownToSpipPage::MAX_LENGTH }} ? 'text-red-400' : 'text-slate-400'"
                        aria-live="polite"
                        aria-atomic="true"
                        x-text="[...($wire.markdown || '')].length.toLocaleString({{ $isEn ? "'en-GB'" : "'fr-FR'" }}) + ' {{ __('messages.home.counter_unit') }}'"
                    ></span>

                    {{-- Clear button --}}
                    <button
                        x-data="{ cleared: false }"
                        @click="
                            $wire.markdown = '';
                            $wire.spip = '';
                            cleared = true;
                            setTimeout(() => cleared = false, 1500)
                        "
                        :class="cleared ? 'text-red-400' : 'text-gray-600 dark:text-slate-300 hover:text-red-400'"
                        class="cursor-pointer transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded"
                        title="{{ __('messages.home.clear_title') }}"
                        aria-label="{{ __('messages.home.clear_aria') }}"
                    >
                        <template x-if="!cleared">
                            <x-icon.trash />
                        </template>
                        <template x-if="cleared">
                            <x-icon.check-circle />
                        </template>
                        <span x-show="cleared" x-cloak class="sr-only" role="status" aria-live="polite">{{ __('messages.home.cleared_status') }}</span>
                    </button>
                </div>
            </div>
            <textarea
                wire:model.live.debounce.50ms="markdown"
                class="flex-1 w-full bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100 p-4 font-mono text-sm resize-none focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500 placeholder-gray-400 dark:placeholder-slate-600 transition-colors"
                placeholder="{{ __('messages.home.placeholder') }}"
                spellcheck="false"
                id="markdown-input"
            ></textarea>
        </div>

        {{-- SPIP output --}}
        <div class="flex flex-col min-h-0">
            <div class="bg-gray-100 dark:bg-slate-800 px-4 py-2 border-b border-gray-300 dark:border-slate-700 flex items-center justify-between transition-colors">
                <span id="spip-label" class="text-gray-700 dark:text-slate-300 text-sm font-semibold uppercase tracking-wide">{{ __('messages.home.spip_label') }}</span>

                <div class="flex items-center gap-3">
                    {{-- Copy button --}}
                    <button
                        x-data="{ copied: false }"
                        @click="
                            if (!$wire.spip) return;
                            navigator.clipboard.writeText(document.getElementById('spip-output').innerText);
                            $wire.countCopy();
                            copied = true;
                            setTimeout(() => copied = false, 1500)
                        "
                        :class="!$wire.spip ? 'text-gray-300/70 dark:text-slate-600 cursor-not-allowed' : (copied ? 'text-emerald-500 dark:text-emerald-400 cursor-pointer' : 'text-gray-600 dark:text-slate-300 hover:text-emerald-500 dark:hover:text-emerald-400 cursor-pointer')"
                        :disabled="!$wire.spip"
                        class="transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded"
                        title="{{ __('messages.home.copy_title') }}"
                        aria-label="{{ __('messages.home.copy_aria') }}"
                    >
                        <template x-if="!copied">
                            <x-icon.clipboard />
                        </template>
                        <template x-if="copied">
                            <x-icon.check-circle />
                        </template>
                        <span x-show="copied" x-cloak class="sr-only" role="status" aria-live="polite">{{ __('messages.home.copied_status') }}</span>
                    </button>
                </div>
            </div>
            <div class="flex-1 w-full bg-gray-50 dark:bg-slate-950 p-4 overflow-auto transition-colors relative" role="region" aria-labelledby="spip-label" aria-live="polite">
                {{-- Empty state --}}
                <div
                    x-show="!$wire.spip"
                    class="absolute inset-0 flex items-center justify-center text-gray-500 dark:text-slate-400"
                >
                    <p class="text-center">
                        <span class="block text-2xl mb-2">→</span>
                        {{ __('messages.home.empty_state') }}
                    </p>
                </div>
                {{-- Output --}}
                <pre id="spip-output" class="font-mono text-sm text-emerald-600 dark:text-emerald-400 whitespace-pre-wrap" x-show="$wire.spip">{{ $spip }}</pre>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-100 dark:bg-slate-800 border-t border-gray-300 dark:border-slate-700 px-4 sm:px-6 py-2 flex flex-col sm:flex-row items-center sm:justify-between gap-1 sm:gap-3 text-xs text-gray-600 dark:text-slate-400 transition-colors">
        <div class="flex flex-col sm:flex-row sm:flex-wrap items-center justify-center gap-x-1.5 gap-y-0.5 text-center">
            <span class="inline-flex items-center gap-1.5">
                {{ __('messages.footer.open_source') }} <a href="https://github.com/Gallyan/md2spip/blob/main/LICENSE" target="_blank" rel="noopener" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors underline">GPL-3.0</a>
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
            <a href="{{ $statsUrl }}" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors">{{ __('messages.footer.stats') }}</a>
            <span aria-hidden="true">•</span>
            <a href="{{ $legalUrl }}" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors">{{ __('messages.footer.legal') }}</a>
        </span>
    </footer>
</div>
