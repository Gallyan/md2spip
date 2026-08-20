@use('Illuminate\Support\Number')
@use('App\Livewire\MarkdownToSpipPage')
@php
    $words = trans('messages.help.words');

    $conversions = [
        ['md' => '# '.$words['title'], 'spip' => '{{{'.$words['title'].'}}}'],
        ['md' => '## '.$words['subtitle'], 'spip' => '{{'.$words['subtitle'].'}}'],
        ['md' => '**'.$words['bold'].'**', 'spip' => '{{'.$words['bold'].'}}'],
        ['md' => '*'.$words['italic'].'*', 'spip' => '{'.$words['italic'].'}'],
        ['md' => '['.$words['link'].'](url)', 'spip' => '['.$words['link'].'->url]'],
        ['md' => '- '.$words['item'], 'spip' => '-* '.$words['item']],
        ['md' => '`'.$words['code'].'`', 'spip' => '<code>'.$words['code'].'</code>'],
        ['md' => '> '.$words['quote'], 'spip' => '<quote>'.$words['quote'].'</quote>'],
        ['md' => $words['text'].'[^1]', 'spip' => $words['text'].'[['.$words['note'].']]'],
    ];
@endphp
{{-- Help button with modal --}}
<div x-data="{ open: false }">
    <button
        x-ref="trigger"
        @click="open = true; $nextTick(() => $refs.closeBtn?.focus())"
        class="cursor-pointer w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 flex items-center justify-center text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800"
        title="{{ __('messages.help.open_title') }}"
        aria-label="{{ __('messages.help.open_aria') }}"
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
        @click="open = false; $nextTick(() => $refs.trigger?.focus())"
        @keydown.escape.window="open = false; $nextTick(() => $refs.trigger?.focus())"
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
                <h3 id="modal-title" class="text-gray-900 dark:text-white font-semibold text-lg">{{ __('messages.help.title') }}</h3>
                <button
                    x-ref="closeBtn"
                    @click="open = false; $nextTick(() => $refs.trigger?.focus())"
                    class="cursor-pointer text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded"
                    aria-label="{{ __('messages.help.close_aria') }}"
                >
                    <x-icon.x-mark class="w-5 h-5" />
                </button>
            </div>

            <div class="space-y-2 text-gray-700 dark:text-slate-200">
                @foreach ($conversions as $row)
                    <div class="flex justify-between text-xs">
                        <code class="text-xs bg-gray-200 dark:bg-slate-800 px-2 py-1 rounded">{{ $row['md'] }}</code>
                        <span class="text-gray-500 dark:text-slate-300">→</span>
                        <code class="text-xs bg-slate-800 px-2 py-1 rounded text-emerald-400">{{ $row['spip'] }}</code>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pt-3 border-t border-gray-300 dark:border-slate-600 text-xs text-gray-600 dark:text-slate-300">
                <p><strong class="text-gray-900 dark:text-white">{{ __('messages.help.limit_label') }}</strong> {{ __('messages.help.limit_value', ['count' => Number::format(MarkdownToSpipPage::MAX_LENGTH)]) }}</p>
            </div>
        </div>
    </div>
</div>
