{{-- Help button with modal --}}
<div x-data="{ open: false }">
    <button
        @click="open = true"
        class="cursor-pointer w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 flex items-center justify-center text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800"
        title="Aide"
        aria-label="Afficher l'aide sur les conversions supportées"
        aria-haspopup="dialog"
        :aria-expanded="open"
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
        @keydown.escape="open = false"
        class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 z-50 flex items-center justify-center p-4"
        style="display: none;"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title"
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
            class="bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg shadow-xl p-6 max-w-md w-full max-h-[80vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-gray-900 dark:text-white font-semibold text-lg">Conversions supportées</h3>
                <button
                    @click="open = false"
                    class="cursor-pointer text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded"
                    aria-label="Fermer la fenêtre d'aide"
                >
                    <x-icon.x-mark class="w-5 h-5" />
                </button>
            </div>

            <div class="space-y-2 text-gray-700 dark:text-slate-200">
            @verbatim
            <div class="flex justify-between">
                <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded"># Titre</code>
                <span class="text-gray-500 dark:text-slate-300">→</span>
                <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{{{Titre}}}</code>
            </div>
            <div class="flex justify-between">
                <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded">## Sous-titre</code>
                <span class="text-gray-500 dark:text-slate-300">→</span>
                <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{{Sous-titre}}</code>
            </div>
            <div class="flex justify-between">
                <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded">**gras**</code>
                <span class="text-gray-500 dark:text-slate-300">→</span>
                <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{{gras}}</code>
            </div>
            <div class="flex justify-between">
                <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded">*italique*</code>
                <span class="text-gray-500 dark:text-slate-300">→</span>
                <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{italique}</code>
            </div>
            @endverbatim
            <div class="flex justify-between text-xs">
                <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded">[lien](url)</code>
                <span class="text-gray-500 dark:text-slate-300">→</span>
                <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">[lien->url]</code>
            </div>
            <div class="flex justify-between text-xs">
                <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded">- item</code>
                <span class="text-gray-500 dark:text-slate-300">→</span>
                <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">-* item</code>
            </div>
            @verbatim
            <div class="flex justify-between text-xs">
                <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded">`code`</code>
                <span class="text-gray-500 dark:text-slate-300">→</span>
                <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400"><code>code</code></code>
            </div>
            <div class="flex justify-between text-xs">
                <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded">> citation</code>
                <span class="text-gray-500 dark:text-slate-300">→</span>
                <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400"><quote>citation</quote></code>
            </div>
            <div class="flex justify-between text-xs">
                <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded">Texte[^1]</code>
                <span class="text-gray-500 dark:text-slate-300">→</span>
                <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">Texte[[note]]</code>
            </div>
            @endverbatim
            </div>

            <div class="mt-4 pt-3 border-t border-gray-300 dark:border-slate-600 text-xs text-gray-600 dark:text-slate-300">
                <p><strong class="text-gray-900 dark:text-white">Limite :</strong> {{ number_format(\App\Livewire\MarkdownToSpipPage::MAX_LENGTH, 0, ',', ' ') }} caractères</p>
            </div>
        </div>
    </div>
</div>
