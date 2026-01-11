<div class="flex flex-col h-screen">
    {{-- Header --}}
    <header class="bg-slate-800 border-b border-slate-700 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">M</span>
            </div>
            <h1 class="text-white font-semibold text-lg">Markdown to SPIP</h1>
        </div>
        <button
            x-data
            @click="navigator.clipboard.writeText(document.getElementById('spip-output').innerText); $el.classList.add('bg-emerald-600'); setTimeout(() => $el.classList.remove('bg-emerald-600'), 200)"
            class="flex items-center gap-2 bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition-colors"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
            </svg>
            <span>Copier SPIP</span>
        </button>
    </header>

    {{-- Main content --}}
    <main class="flex-1 grid grid-cols-1 md:grid-cols-2 min-h-0">
        {{-- Markdown input --}}
        <div class="flex flex-col border-r border-slate-700 min-h-0">
            <div class="bg-slate-800 px-4 py-2 border-b border-slate-700">
                <span class="text-slate-400 text-sm font-medium">MARKDOWN</span>
            </div>
            <textarea
                wire:model.live="markdown"
                class="flex-1 w-full bg-slate-900 text-slate-100 p-4 font-mono text-sm resize-none focus:outline-none placeholder-slate-600"
                placeholder="Collez ou tapez votre Markdown ici..."
                spellcheck="false"
            ></textarea>
        </div>

        {{-- SPIP output --}}
        <div class="flex flex-col min-h-0">
            <div class="bg-slate-800 px-4 py-2 border-b border-slate-700">
                <span class="text-slate-400 text-sm font-medium">SPIP</span>
            </div>
            <pre id="spip-output" class="flex-1 w-full bg-slate-950 text-emerald-400 p-4 font-mono text-sm overflow-auto whitespace-pre-wrap">{{ $spip }}</pre>
        </div>
    </main>
</div>
