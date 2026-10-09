@use('App\Support\LocaleUrls')
@use('App\Support\MarkdownToSpipConverter')
@use('App\Support\SyntaxReference')
@php
    $isEn = LocaleUrls::isEnglish();
    $exampleMarkdown = (string) __('messages.guide.example_markdown');
    $section = 'bg-white dark:bg-slate-800/50 rounded-xl p-6 sm:p-8 border border-gray-200 dark:border-slate-700 transition-colors';
    $h2 = 'text-2xl font-semibold text-gray-900 dark:text-white mb-5';
    $text = 'text-gray-700 dark:text-slate-200 text-lg';
    $link = 'text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 underline';
@endphp
<x-layouts.app :title="__('messages.guide.page_title')" :description="__('messages.guide.description')">
    <main class="w-full min-w-0 max-w-4xl mx-auto px-4 sm:px-6 py-16">
        <x-page-header :back="__('messages.guide.back')" :title="__('messages.guide.h1')" margin="mb-8" />

        <p class="text-xl text-gray-700 dark:text-slate-200 mb-12 leading-relaxed">{{ __('messages.guide.lead') }}</p>

        <div class="space-y-12 leading-relaxed">
            <section class="{{ $section }}">
                <h2 class="{{ $h2 }}">{{ __('messages.guide.use_cases_title') }}</h2>
                <p class="{{ $text }} mb-4">{{ __('messages.guide.use_cases_intro') }}</p>
                <ul class="list-disc ml-6 space-y-2 {{ $text }}">
                    @foreach ((array) __('messages.guide.use_cases') as $useCase)
                        <li>{{ $useCase }}</li>
                    @endforeach
                </ul>
            </section>

            <section class="{{ $section }}">
                <h2 class="{{ $h2 }}">{{ __('messages.guide.syntax_title') }}</h2>
                <div class="overflow-x-auto rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500" tabindex="0" role="region" aria-label="{{ __('messages.guide.syntax_caption') }}">
                    <table class="w-full text-left text-sm">
                        <caption class="sr-only">{{ __('messages.guide.syntax_caption') }}</caption>
                        <thead>
                            <tr class="border-b border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white">
                                <th scope="col" class="py-2 pr-4 font-semibold">Markdown</th>
                                <th scope="col" class="py-2 font-semibold">SPIP</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-slate-200">
                            @foreach (SyntaxReference::all() as $row)
                                <tr class="border-b border-gray-200 dark:border-slate-700 last:border-0">
                                    <td class="py-2 pr-4 align-top"><code class="whitespace-pre font-mono">{{ $row['md'] }}</code></td>
                                    <td class="py-2 align-top"><code class="whitespace-pre font-mono text-emerald-700 dark:text-emerald-400">{{ $row['spip'] }}</code></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-10 mb-4">{{ __('messages.guide.example_title') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <figure class="min-w-0">
                        <figcaption class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-slate-300 mb-2">{{ __('messages.home.markdown_label') }}</figcaption>
                        <pre class="font-mono text-sm whitespace-pre-wrap bg-gray-100 dark:bg-slate-900/50 border border-gray-300 dark:border-slate-600 rounded-lg p-4 text-gray-800 dark:text-slate-200">{{ $exampleMarkdown }}</pre>
                    </figure>
                    <figure class="min-w-0">
                        <figcaption class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-slate-300 mb-2">{{ __('messages.home.spip_label') }}</figcaption>
                        <pre class="font-mono text-sm whitespace-pre-wrap bg-gray-100 dark:bg-slate-900/50 border border-gray-300 dark:border-slate-600 rounded-lg p-4 text-emerald-700 dark:text-emerald-400">{{ MarkdownToSpipConverter::convert($exampleMarkdown) }}</pre>
                    </figure>
                </div>
            </section>

            <section class="{{ $section }}">
                <h2 class="{{ $h2 }}">{{ __('messages.guide.limits_title') }}</h2>
                <ul class="space-y-3 {{ $text }}">
                    @foreach ((array) __('messages.guide.limits') as $limit)
                        <li><strong class="text-gray-900 dark:text-white">{{ $limit['label'] }}.</strong> {{ $limit['text'] }}</li>
                    @endforeach
                </ul>
            </section>

            <section class="{{ $section }}">
                <h2 class="{{ $h2 }}">{{ __('messages.guide.privacy_title') }}</h2>
                <p class="{{ $text }}">{{ __('messages.guide.privacy_server') }}</p>
                <p class="{{ $text }} mt-4">
                    {{ __('messages.guide.privacy_stats') }}
                    <a href="{{ $isEn ? '/en/stats' : '/stats' }}" class="{{ $link }}">{{ __('messages.guide.privacy_stats_link') }}</a>.
                </p>
            </section>

            <section class="{{ $section }}">
                <h2 class="{{ $h2 }}">{{ __('messages.guide.faq_title') }}</h2>
                <div class="space-y-6">
                    @foreach ((array) __('messages.seo.faqs') as $faq)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $faq['q'] }}</h3>
                            <p class="{{ $text }}">{{ $faq['a'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="{{ $section }}">
                <h2 class="{{ $h2 }}">{{ __('messages.guide.project_title') }}</h2>
                <p class="{{ $text }}">{{ __('messages.guide.project_text') }}</p>
                <p class="{{ $text }} mt-4">
                    <a href="https://github.com/Gallyan/md2spip" target="_blank" rel="noopener" class="{{ $link }}">github.com/Gallyan/md2spip</a>
                </p>
            </section>
        </div>
    </main>
</x-layouts.app>
