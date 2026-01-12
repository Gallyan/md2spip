<div class="flex flex-col h-screen">
    {{-- Header --}}
    <header class="bg-slate-800 border-b border-slate-700 px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-8 h-8">
                <rect width="32" height="32" rx="6" fill="#1e293b" stroke="#334155" stroke-width="1"/>
                <text x="4" y="20" font-family="monospace" font-size="11" font-weight="bold" fill="#94a3b8">#</text>
                <path d="M13 16 L19 16 M17 13 L20 16 L17 19" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <text x="21" y="20" font-family="monospace" font-size="10" font-weight="bold" fill="#10b981">{</text>
            </svg>
            <h1 class="text-white font-semibold text-lg">Markdown to SPIP</h1>

            {{-- Help button with popover --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    @click.away="open = false"
                    class="w-6 h-6 rounded-full bg-slate-700 hover:bg-slate-600 text-slate-300 flex items-center justify-center text-xs font-semibold transition-colors"
                    title="Aide"
                >
                    ?
                </button>

                {{-- Popover --}}
                <div
                    x-show="open"
                    x-transition
                    class="absolute top-8 left-0 w-80 bg-slate-700 border border-slate-600 rounded-lg shadow-xl p-4 z-50 text-sm"
                >
                    <h3 class="text-white font-semibold mb-3">Conversions supportées</h3>
                    <div class="space-y-2 text-slate-200">
                        @verbatim
                        <div class="flex justify-between">
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded"># Titre</code>
                            <span class="text-slate-400">→</span>
                            <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{{{Titre}}}</code>
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
                        @endverbatim
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-600 text-xs text-slate-400">
                        <p><strong class="text-white">Limite :</strong> {{ number_format(\App\Livewire\MarkdownToSpipPage::MAX_LENGTH, 0, ',', ' ') }} caractères</p>
                        <p class="mt-1"><strong class="text-white">Conversion :</strong> Temps réel (debounce 50ms)</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            {{-- Request counter (minute glissante, mise à jour auto) --}}
            <div wire:poll.1s class="text-xs text-slate-500">
                <span class="font-mono">{{ $this->requestCount }}/300 req/min</span>
            </div>

            {{-- Copy button --}}
            <button
                x-data="{ copied: false }"
                @click="
                    navigator.clipboard.writeText(document.getElementById('spip-output').innerText);
                    copied = true;
                    setTimeout(() => copied = false, 1500)
                "
                :class="copied ? 'bg-emerald-600' : 'bg-slate-700 hover:bg-slate-600'"
                class="flex items-center justify-center gap-2 text-white px-4 py-2 rounded-lg transition-colors min-w-[150px]"
            >
                <template x-if="!copied">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                    </svg>
                </template>
                <template x-if="copied">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </template>
                <span x-text="copied ? 'Copié !' : 'Copier SPIP'"></span>
            </button>
        </div>
    </header>

    {{-- Main content --}}
    <main class="flex-1 grid grid-cols-1 md:grid-cols-2 min-h-0">
        {{-- Markdown input --}}
        <div class="flex flex-col border-r border-slate-700 min-h-0">
            <div class="bg-slate-800 px-4 py-2 border-b border-slate-700 flex items-center justify-between">
                <span class="text-slate-400 text-sm font-medium uppercase">Markdown</span>
                {{-- Character counter --}}
                <span class="text-xs font-mono"
                    :class="$wire.characterCount > {{ \App\Livewire\MarkdownToSpipPage::MAX_LENGTH }} ? 'text-red-400' : 'text-slate-500'">
                    {{ number_format($this->characterCount, 0, ',', ' ') }} / {{ number_format(\App\Livewire\MarkdownToSpipPage::MAX_LENGTH, 0, ',', ' ') }}
                </span>
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
            <div class="bg-slate-800 px-4 py-2 border-b border-slate-700 flex items-center">
                <span class="text-slate-400 text-sm font-medium uppercase">Spip</span>
            </div>
            <pre id="spip-output" class="flex-1 w-full bg-slate-950 text-emerald-400 p-4 font-mono text-sm overflow-auto whitespace-pre-wrap">{{ $spip }}</pre>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-800 border-t border-slate-700 px-6 py-2 flex items-center justify-between text-xs text-slate-500">
        <span>&copy; {{ date('Y') }} Guillaume Orsal - Tous droits réservés</span>
        <a href="/mentions-legales" class="hover:text-slate-300 transition-colors">Mentions légales</a>
    </footer>
</div>
