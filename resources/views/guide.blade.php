@use('App\Http\Controllers\Api\ConvertController')
@use('App\Livewire\MarkdownToSpipPage')
@use('App\Support\LocaleUrls')
@use('App\Support\MarkdownToSpipConverter')
@use('App\Support\SyntaxReference')
@use('Illuminate\Support\Number')
@php
    $isEn = LocaleUrls::isEnglish();
    $exampleMarkdown = (string) __('messages.guide.example_markdown');
    $section = 'bg-white dark:bg-slate-800/50 rounded-xl p-6 sm:p-8 border border-gray-200 dark:border-slate-700 transition-colors scroll-mt-8';
    $h2 = 'text-2xl font-semibold text-gray-900 dark:text-white mb-5';
    $text = 'text-gray-700 dark:text-slate-200 text-lg';
    $link = 'text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 underline';
    $toc = [
        'use-cases' => __('messages.guide.use_cases_title'),
        'syntax' => __('messages.guide.syntax_title'),
        'limits' => __('messages.guide.limits_title'),
        'privacy' => __('messages.guide.privacy_title'),
        'api' => __('messages.guide.api_title'),
        'faq' => __('messages.guide.faq_title'),
        'project' => __('messages.guide.project_title'),
    ];
@endphp
<x-layouts.app :title="__('messages.guide.page_title')" :description="__('messages.guide.description')">
    <main class="flex-1 w-full min-w-0 max-w-4xl lg:max-w-6xl mx-auto px-4 sm:px-6 py-16">
        <x-page-header :back="__('messages.guide.back')" :title="__('messages.guide.h1')" margin="mb-8" />

        <div class="lg:grid lg:grid-cols-[13rem_minmax(0,1fr)] lg:gap-x-12">
            <p class="text-xl text-gray-700 dark:text-slate-200 mb-12 leading-relaxed lg:col-start-2 lg:row-start-1">{{ __('messages.guide.lead') }}</p>

            <nav
                id="toc"
                aria-label="{{ __('messages.guide.toc') }}"
                class="scroll-mt-8 mb-12 lg:mb-0 lg:col-start-1 lg:row-start-1 lg:row-span-2 lg:sticky lg:top-8 lg:self-start"
                x-data="{ active: '' }"
                x-init="const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            active = entry.target.id;
                        }
                    });
                }, { rootMargin: '0px 0px -70% 0px' });
                const sections = document.querySelectorAll('main section[id]');
                sections.forEach((section) => observer.observe(section));
                window.addEventListener('scroll', () => {
                    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2) {
                        active = sections[sections.length - 1].id;
                    }
                }, { passive: true });"
            >
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-slate-300 mb-3">{{ __('messages.guide.toc') }}</p>
                <ul class="border-l border-gray-300 dark:border-slate-600 space-y-1 text-sm">
                    @foreach ($toc as $id => $title)
                        <li>
                            <a
                                href="#{{ $id }}"
                                class="block -ml-px border-l-2 pl-4 py-1 transition-colors"
                                :class="active === '{{ $id }}'
                                    ? 'border-emerald-500 text-emerald-700 dark:text-emerald-400 font-medium'
                                    : 'border-transparent text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white hover:border-gray-400 dark:hover:border-slate-400'"
                                :aria-current="active === '{{ $id }}' ? 'location' : null"
                            >{{ $title }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="min-w-0 lg:col-start-2">
                <div class="space-y-12 leading-relaxed">
                    <section id="use-cases" class="{{ $section }}">
                        <h2 class="{{ $h2 }}">{{ __('messages.guide.use_cases_heading') }}</h2>
                        <p class="{{ $text }} mb-4">{{ __('messages.guide.use_cases_intro') }}</p>
                        <ul class="list-disc ml-6 space-y-2 {{ $text }}">
                            @foreach ((array) __('messages.guide.use_cases') as $useCase)
                                <li>{{ $useCase }}</li>
                            @endforeach
                        </ul>
                    </section>

                    <section id="syntax" class="{{ $section }}">
                        <h2 class="{{ $h2 }}">{{ __('messages.guide.syntax_heading') }}</h2>
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

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-10 mb-4">{{ __('messages.guide.example_heading') }}</h3>
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

                    <section id="limits" class="{{ $section }}">
                        <h2 class="{{ $h2 }}">{{ __('messages.guide.limits_heading') }}</h2>
                        <ul class="space-y-3 {{ $text }}">
                            @foreach ((array) __('messages.guide.limits') as $limit)
                                <li><strong class="text-gray-900 dark:text-white">{{ $limit['label'] }}.</strong> {{ $limit['text'] }}</li>
                            @endforeach
                        </ul>
                    </section>

                    <section id="privacy" class="{{ $section }}">
                        <h2 class="{{ $h2 }}">{{ __('messages.guide.privacy_heading') }}</h2>
                        <p class="{{ $text }}">{{ __('messages.guide.privacy_server') }}</p>
                        <p class="{{ $text }} mt-4">
                            {{ __('messages.guide.privacy_stats') }}
                            <a href="{{ $isEn ? '/en/stats' : '/stats' }}" class="{{ $link }}">{{ __('messages.guide.privacy_stats_link') }}</a>.
                        </p>
                    </section>

                    <section id="api" class="{{ $section }}">
                        <h2 class="{{ $h2 }}">{{ __('messages.guide.api_heading') }}</h2>
                        <p class="{{ $text }}">{{ __('messages.guide.api_intro') }}</p>
                        <pre class="font-mono text-sm whitespace-pre-wrap break-all bg-gray-100 dark:bg-slate-900/50 border border-gray-300 dark:border-slate-600 rounded-lg p-4 my-4 text-gray-800 dark:text-slate-200">curl --data-binary @article.md {{ url('/api/convert') }}</pre>
                        <p class="{{ $text }}">{{ __('messages.guide.api_limits', ['max' => (string) Number::format(MarkdownToSpipPage::MAX_LENGTH), 'attempts' => ConvertController::MAX_ATTEMPTS]) }}</p>
                        <p class="{{ $text }} mt-4">
                            {{ __('messages.guide.api_docs') }}
                            <a href="/openapi.json" class="{{ $link }}">openapi.json</a>,
                            <a href="/llms.txt" class="{{ $link }}">llms.txt</a>.
                        </p>
                    </section>

                    <section id="faq" class="{{ $section }}">
                        <h2 class="{{ $h2 }}">{{ __('messages.guide.faq_heading') }}</h2>
                        <div class="divide-y divide-gray-200 dark:divide-slate-700">
                            @foreach ((array) __('messages.seo.faqs') as $faq)
                                <div class="py-6 first:pt-0 last:pb-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $faq['q'] }}</h3>
                                    <div class="mt-3 pl-4 border-l-2 border-emerald-500 space-y-1">
                                        <p class="{{ $text }}">{{ $faq['a'] }}</p>
                                        @isset($faq['link'])
                                            <p class="{{ $text }}">
                                                <a href="{{ $faq['link']['url'] }}" target="_blank" rel="noopener" class="{{ $link }}">{{ $faq['link']['label'] }}</a>
                                            </p>
                                        @endisset
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section id="project" class="{{ $section }}">
                        <h2 class="{{ $h2 }}">{{ __('messages.guide.project_heading') }}</h2>
                        <p class="{{ $text }}">{!! str_replace(':author', '<a href="https://www.orsal.fr" class="'.$link.'">Guillaume Orsal</a>', e(__('messages.guide.project_text'))) !!}</p>
                        <p class="{{ $text }} mt-4">
                            <a href="https://github.com/Gallyan/md2spip" target="_blank" rel="noopener" class="{{ $link }}">github.com/Gallyan/md2spip</a>
                        </p>
                    </section>
                </div>
            </div>
        </div>

        <a
            href="#toc"
            x-data="{ visible: false }"
            x-init="new IntersectionObserver(([entry]) => {
                visible = ! entry.isIntersecting && entry.boundingClientRect.top < 0;
            }).observe(document.getElementById('toc'))"
            x-show="visible"
            x-transition.opacity
            x-cloak
            class="lg:hidden fixed bottom-4 right-4 z-10 inline-flex items-center gap-1.5 px-4 py-2 rounded-full shadow-lg bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
        >
            <x-icon.arrow-up class="w-4 h-4" />
            {{ __('messages.guide.toc') }}
        </a>
    </main>

    <x-site-footer />
</x-layouts.app>
