<div class="flex flex-col h-screen"
    x-data="{
        init() {
            // Restaurer le texte depuis localStorage au chargement
            const saved = localStorage.getItem('md2spip-markdown');
            if (saved && saved !== '') {
                $wire.markdown = saved;
            }
        }
    }"
    x-effect="localStorage.setItem('md2spip-markdown', $wire.markdown || '')">
    {{-- Header --}}
    <header class="bg-slate-800 border-b border-slate-700 px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="flex flex-col">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-8 h-8">
                        <rect width="32" height="32" rx="6" fill="#1e293b" stroke="#334155" stroke-width="1"/>
                        <text x="4" y="20" font-family="monospace" font-size="11" font-weight="bold" fill="#94a3b8">#</text>
                        <path d="M13 16 L19 16 M17 13 L20 16 L17 19" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <text x="21" y="20" font-family="monospace" font-size="10" font-weight="bold" fill="#10b981">{</text>
                    </svg>
                    <div>
                        <h1 class="text-white font-semibold text-lg leading-tight">Markdown to SPIP</h1>
                        <h2 class="hidden md:block text-slate-400 text-xs font-normal">Convertisseur en ligne gratuit et instantané</h2>
                    </div>
                </div>
            </div>

            {{-- Help button with modal --}}
            <div x-data="{ open: false }">
                <button
                    @click="open = true"
                    class="w-6 h-6 rounded-full bg-slate-700 hover:bg-slate-600 text-slate-300 flex items-center justify-center text-xs font-semibold transition-colors"
                    title="Aide"
                >
                    ?
                </button>

                {{-- Modal backdrop --}}
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click="open = false"
                    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
                    style="display: none;"
                >
                    {{-- Modal content --}}
                    <div
                        @click.stop
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="bg-slate-700 border border-slate-600 rounded-lg shadow-xl p-6 max-w-md w-full max-h-[80vh] overflow-y-auto"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-white font-semibold text-lg">Conversions supportées</h3>
                            <button @click="open = false" class="text-slate-400 hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-2 text-slate-200">
                        @verbatim
                        <div class="flex justify-between">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded"># Titre</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{{{Titre}}}</code>
                        </div>
                        <div class="flex justify-between">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded">## Sous-titre</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{{Sous-titre}}</code>
                        </div>
                        <div class="flex justify-between">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded">**gras**</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{{gras}}</code>
                        </div>
                        <div class="flex justify-between">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded">*italique*</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{italique}</code>
                        </div>
                        @endverbatim
                        <div class="flex justify-between text-xs">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded">[lien](url)</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">[lien->url]</code>
                        </div>
                        <div class="flex justify-between text-xs">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded">- item</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">-* item</code>
                        </div>
                        @verbatim
                        <div class="flex justify-between text-xs">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded">`code`</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400"><code>code</code></code>
                        </div>
                        <div class="flex justify-between text-xs">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded">> citation</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400"><quote>citation</quote></code>
                        </div>
                        <div class="flex justify-between text-xs">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded">Texte[^1]</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">Texte[[note]]</code>
                        </div>
                        @endverbatim
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-600 text-xs text-slate-400">
                            <p><strong class="text-white">Limite :</strong> {{ number_format(\App\Livewire\MarkdownToSpipPage::MAX_LENGTH, 0, ',', ' ') }} caractères</p>
                            <p class="mt-1"><strong class="text-white">Conversion :</strong> Temps réel (debounce 50ms)</p>
                        </div>
                    </div>
                </div>
            </div>

    </header>

    {{-- Main content --}}
    <main class="flex-1 grid grid-cols-1 md:grid-cols-2 min-h-0">
        {{-- Markdown input --}}
        <div class="flex flex-col border-r border-slate-700 min-h-0">
            <div class="bg-slate-800 px-4 py-2 border-b border-slate-700 flex items-center justify-between">
                <span class="text-slate-400 text-sm font-medium uppercase">Markdown</span>

                <div class="flex items-center gap-3">
                    {{-- Character counter --}}
                    <span class="text-xs font-mono"
                        :class="$wire.characterCount > {{ \App\Livewire\MarkdownToSpipPage::MAX_LENGTH }} ? 'text-red-400' : 'text-slate-500'">
                        {{ number_format($this->characterCount, 0, ',', ' ') }} / 100k car.
                    </span>

                    {{-- Clear button --}}
                    <button
                        x-data="{ cleared: false }"
                        @click="
                            $wire.markdown = '';
                            cleared = true;
                            setTimeout(() => cleared = false, 1500)
                        "
                        :class="cleared ? 'text-red-400' : 'text-slate-400 hover:text-red-400'"
                        class="transition-colors"
                        title="Effacer tout le texte"
                    >
                        <template x-if="!cleared">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </template>
                        <template x-if="cleared">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </template>
                    </button>
                </div>
            </div>
            <textarea
                wire:model.live.debounce.50ms="markdown"
                class="flex-1 w-full bg-slate-900 text-slate-100 p-4 font-mono text-sm resize-none focus:outline-none placeholder-slate-600"
                placeholder="Collez ou tapez votre Markdown ici..."
                spellcheck="false"
            ></textarea>
        </div>

        {{-- SPIP output --}}
        <div class="flex flex-col min-h-0">
            <div class="bg-slate-800 px-4 py-2 border-b border-slate-700 flex items-center justify-between">
                <span class="text-slate-400 text-sm font-medium uppercase">Spip</span>

                <div class="flex items-center gap-3">
                    {{-- Request counter (minute glissante, mise à jour auto) --}}
                    <span wire:poll.1s class="text-xs font-mono text-slate-500">
                        {{ $this->requestCount }}/300 req/min
                    </span>

                    {{-- Copy button --}}
                    <button
                        x-data="{ copied: false }"
                        @click="
                            navigator.clipboard.writeText(document.getElementById('spip-output').innerText);
                            copied = true;
                            setTimeout(() => copied = false, 1500)
                        "
                        :class="copied ? 'text-emerald-400' : 'text-slate-400 hover:text-emerald-400'"
                        class="transition-colors"
                        title="Copier le résultat SPIP"
                    >
                        <template x-if="!copied">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                            </svg>
                        </template>
                        <template x-if="copied">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </template>
                    </button>
                </div>
            </div>
            <pre id="spip-output" class="flex-1 w-full bg-slate-950 text-emerald-400 p-4 font-mono text-sm overflow-auto whitespace-pre-wrap">{{ $spip }}</pre>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-800 border-t border-slate-700 px-6 py-2 flex items-center justify-between text-xs text-slate-500">
        <span>Projet open source <a href="https://github.com/Gallyan/md2spip/blob/main/LICENSE" target="_blank" rel="noopener" class="hover:text-slate-300 transition-colors underline">GPL-3.0</a> • Créé par <a href="https://www.orsal.fr" target="_blank" rel="noopener" class="hover:text-slate-300 transition-colors underline">Guillaume Orsal</a> en 2026</span>
        <a href="/mentions-legales" class="hover:text-slate-300 transition-colors">Mentions légales</a>
    </footer>
</div>
