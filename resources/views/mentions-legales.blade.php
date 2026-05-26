<x-layouts.app :title="__('messages.legal.page_title')" robots="noindex, nofollow">
    <x-slot:head>
        <style>
            .protected-email { font-size: 0; }
            .protected-email::before {
                font-size: 1.125rem;
                content: attr(data-email-user) "@" attr(data-email-domain);
            }
        </style>
    </x-slot:head>
    @php
        $locale = \App\Support\LocaleUrls::locale();
        $email = (string) (config('legal.contact_email') ?? config('mail.from.address') ?? '');
        [$emailUser, $emailDomain] = $email && str_contains($email, '@')
            ? explode('@', $email, 2)
            : [null, null];
        $website = (string) (config('legal.social.website') ?? '');
    @endphp
    <div class="max-w-4xl mx-auto px-6 py-16">
        <x-page-header :back="__('messages.legal.back')" :title="__('messages.legal.h1')" margin="mb-16" />

        <div class="space-y-12 leading-relaxed">
            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">{{ __('messages.legal.editor') }}</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg">
                    <strong class="text-gray-900 dark:text-white">{{ config('legal.editor_name') }}</strong><br>
                    @if (config('legal.editor_address'))
                        {{ config('legal.editor_address') }}<br>
                    @endif
                    @if (config('legal.editor_phone'))
                        {{ __('messages.legal.editor_phone_label') }} {{ config('legal.editor_phone') }}<br>
                    @endif
                    @if ($emailUser && $emailDomain)
                        {{ __('messages.legal.editor_email_label') }} <a href="{{ $locale === 'en' ? '/en/contact' : '/contact' }}" class="hover:text-emerald-600 dark:hover:text-emerald-300 transition-colors"><span class="protected-email text-emerald-600 dark:text-emerald-400" data-email-user="{{ $emailUser }}" data-email-domain="{{ $emailDomain }}">[email protected]</span></a><br>
                    @endif
                    @if ($website)
                        {{ __('messages.legal.editor_website_label') }} <a href="{{ $website }}" target="_blank" rel="noopener" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 underline">{{ preg_replace('#^https?://#', '', $website) }}</a>
                    @endif
                </p>
            </section>

            @if (config('legal.hosting.name'))
                <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">{{ __('messages.legal.hosting') }}</h2>
                    <p class="text-gray-700 dark:text-slate-200 text-lg">
                        {{ __('messages.legal.hosting_intro') }}<br>
                        <strong class="text-gray-900 dark:text-white">{{ config('legal.hosting.name') }}</strong><br>
                        @if (config('legal.hosting.address'))
                            {{ config('legal.hosting.address') }}<br>
                        @endif
                        @if (config('legal.hosting.phone'))
                            {{ __('messages.legal.hosting_phone_label') }} {{ config('legal.hosting.phone') }}
                        @endif
                    </p>
                </section>
            @endif

            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">{{ __('messages.legal.free_software') }}</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg">{{ __('messages.legal.free_software_intro') }} <strong class="text-gray-900 dark:text-white">GNU GPL-3.0</strong>.</p>
                <p class="mt-4 text-gray-700 dark:text-slate-200 text-lg">
                    {{ __('messages.legal.free_software_freedom') }}<br>
                    {{ __('messages.legal.free_software_source') }} <a href="https://github.com/Gallyan/md2spip" target="_blank" rel="noopener" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 underline">github.com/Gallyan/md2spip</a>
                </p>
            </section>

            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">{{ __('messages.legal.personal_data') }}</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg mb-4">{{ __('messages.legal.personal_data_intro') }} <strong class="text-gray-900 dark:text-white">{{ __('messages.legal.personal_data_intro_strong') }}</strong>.</p>
                <ul class="list-disc list-inside space-y-2 ml-4 text-gray-700 dark:text-slate-200 text-lg">
                    @foreach ((array) __('messages.legal.personal_data_list') as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
                <div class="mt-6 p-4 bg-gray-100 dark:bg-slate-900/50 rounded-lg border border-gray-300 dark:border-slate-600 transition-colors">
                    <p class="text-gray-700 dark:text-slate-200 text-lg"><strong class="text-gray-900 dark:text-white">{{ __('messages.legal.personal_data_online_label') }}</strong> {{ __('messages.legal.personal_data_online_text') }} <strong class="text-gray-900 dark:text-white">{{ __('messages.legal.personal_data_online_text_strong') }}</strong>{{ __('messages.legal.personal_data_online_text_after') }}</p>
                    <p class="mt-3 text-gray-700 dark:text-slate-200 text-lg"><strong class="text-gray-900 dark:text-white">{{ __('messages.legal.personal_data_local_label') }}</strong> {{ __('messages.legal.personal_data_local_text') }}</p>
                </div>
            </section>

            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">{{ __('messages.legal.cookies') }}</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg">{{ __('messages.legal.cookies_p1') }}</p>
                <p class="mt-4 text-gray-700 dark:text-slate-200 text-lg">{{ __('messages.legal.cookies_p2_intro') }} <strong class="text-gray-900 dark:text-white">{{ __('messages.legal.cookies_p2_strong') }}</strong> {{ __('messages.legal.cookies_p2_outro') }}</p>
            </section>

            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">{{ __('messages.legal.responsibility') }}</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg">{{ __('messages.legal.responsibility_p1') }}</p>
                <p class="mt-4 text-gray-700 dark:text-slate-200 text-lg">{{ __('messages.legal.responsibility_p2') }}</p>
            </section>

            <section class="text-sm text-gray-600 dark:text-slate-400 pt-8 border-t border-gray-300 dark:border-slate-700">
                <p>{{ __('messages.legal.last_update') }} {{ $locale === 'en' ? date('Y-m-d') : date('d/m/Y') }}</p>
            </section>
        </div>
    </div>
</x-layouts.app>
