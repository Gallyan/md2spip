<div class="flex flex-col h-screen"
    x-data="{
        darkMode: localStorage.getItem('md2spip-theme') !== 'light',
        init() {
            // Restaurer le texte depuis localStorage au chargement
            const saved = localStorage.getItem('md2spip-markdown');
            if (saved && saved !== '') {
                $wire.markdown = saved;
            }
            // Appliquer le thème au chargement
            this.updateTheme();
        },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            this.updateTheme();
        },
        updateTheme() {
            localStorage.setItem('md2spip-theme', this.darkMode ? 'dark' : 'light');
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
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
    {{-- Header --}}
    <header class="bg-gray-100 dark:bg-slate-800 border-b border-gray-300 dark:border-slate-700 px-6 py-3 flex items-center justify-between transition-colors">
        <div class="flex items-center gap-3">
            <div class="flex flex-col">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-10 h-10">
                        <rect width="32" height="32" rx="6" fill="#1e293b" stroke="#334155" stroke-width="1"/>
                        <text x="4" y="20" font-family="monospace" font-size="11" font-weight="bold" fill="#94a3b8">#</text>
                        <path d="M13 16 L19 16 M17 13 L20 16 L17 19" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <text x="21" y="20" font-family="monospace" font-size="10" font-weight="bold" fill="#10b981">{</text>
                    </svg>
                    <div>
                        <h1 class="text-gray-900 dark:text-white font-semibold text-lg leading-tight">Markdown to SPIP</h1>
                        <h2 class="hidden md:block text-gray-600 dark:text-slate-300 text-xs font-normal">Convertisseur en ligne gratuit et instantané</h2>
                    </div>
                </div>
            </div>

            <x-help-modal />
        </div>

        {{-- Theme toggle button (right side) --}}
        <button
            @click="toggleTheme()"
            class="cursor-pointer w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800"
            :title="darkMode ? 'Passer en mode clair' : 'Passer en mode sombre'"
            :aria-label="darkMode ? 'Activer le mode clair' : 'Activer le mode sombre'"
        >
            <x-icon.sun x-show="darkMode" />
            <x-icon.moon x-show="!darkMode" />
        </button>
    </header>

    {{-- Main content --}}
    <main class="flex-1 grid grid-cols-1 md:grid-cols-2 min-h-0">
        {{-- Markdown input --}}
        <div class="flex flex-col border-r border-gray-300 dark:border-slate-700 min-h-0">
            <div class="bg-gray-100 dark:bg-slate-800 px-4 py-2 border-b border-gray-300 dark:border-slate-700 flex items-center justify-between transition-colors">
                <label for="markdown-input" id="markdown-label" class="text-gray-700 dark:text-slate-300 text-sm font-semibold uppercase tracking-wide">Markdown</label>

                <div class="flex items-center gap-3">
                    {{-- Character counter --}}
                    <span
                        class="text-xs font-mono"
                        :class="($wire.markdown || '').length > {{ \App\Livewire\MarkdownToSpipPage::MAX_LENGTH }} ? 'text-red-400' : 'text-slate-400'"
                        aria-live="polite"
                        aria-atomic="true"
                        x-text="[...($wire.markdown || '')].length.toLocaleString('fr-FR') + ' / 100k car.'"
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
                        title="Effacer tout le texte"
                        aria-label="Effacer tout le texte Markdown"
                    >
                        <template x-if="!cleared">
                            <x-icon.trash />
                        </template>
                        <template x-if="cleared">
                            <x-icon.check-circle />
                        </template>
                        <span x-show="cleared" x-cloak class="sr-only" role="status" aria-live="polite">Texte effacé</span>
                    </button>
                </div>
            </div>
            <textarea
                wire:model.live.debounce.50ms="markdown"
                class="flex-1 w-full bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100 p-4 font-mono text-sm resize-none focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500 placeholder-gray-400 dark:placeholder-slate-600 transition-colors"
                placeholder="Collez ou tapez votre Markdown ici..."
                spellcheck="false"
                id="markdown-input"
            ></textarea>
        </div>

        {{-- SPIP output --}}
        <div class="flex flex-col min-h-0">
            <div class="bg-gray-100 dark:bg-slate-800 px-4 py-2 border-b border-gray-300 dark:border-slate-700 flex items-center justify-between transition-colors">
                <span id="spip-label" class="text-gray-700 dark:text-slate-300 text-sm font-semibold uppercase tracking-wide">Spip</span>

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
                        title="Copier le résultat SPIP"
                        aria-label="Copier le résultat SPIP dans le presse-papier"
                    >
                        <template x-if="!copied">
                            <x-icon.clipboard />
                        </template>
                        <template x-if="copied">
                            <x-icon.check-circle />
                        </template>
                        <span x-show="copied" x-cloak class="sr-only" role="status" aria-live="polite">Texte copié dans le presse-papier</span>
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
                        Le résultat SPIP apparaîtra ici
                    </p>
                </div>
                {{-- Output --}}
                <pre id="spip-output" class="font-mono text-sm text-emerald-600 dark:text-emerald-400 whitespace-pre-wrap" x-show="$wire.spip">{{ $spip }}</pre>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-100 dark:bg-slate-800 border-t border-gray-300 dark:border-slate-700 px-6 py-2 flex items-center justify-between text-xs text-gray-600 dark:text-slate-400 transition-colors">
        <span class="inline-flex items-center gap-1.5">
            Projet open source <a href="https://github.com/Gallyan/md2spip/blob/main/LICENSE" target="_blank" rel="noopener" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors underline">GPL-3.0</a>
            <span aria-hidden="true">•</span>
            <a href="https://github.com/Gallyan/md2spip" target="_blank" rel="noopener" aria-label="Code source sur GitHub" class="inline-flex hover:text-gray-900 dark:hover:text-slate-300 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded">
                <x-icon.github class="w-3.5 h-3.5" />
            </a>
            <span aria-hidden="true">•</span>
            Créé par <a href="https://www.orsal.fr" target="_blank" rel="noopener" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors underline">Guillaume Orsal</a> en 2026
        </span>
        <span class="inline-flex items-center gap-1.5">
            <a href="/stats" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors">Stats</a>
            <span aria-hidden="true">•</span>
            <a href="/mentions-legales" class="hover:text-gray-900 dark:hover:text-slate-300 transition-colors">Mentions légales</a>
        </span>
    </footer>
</div>
