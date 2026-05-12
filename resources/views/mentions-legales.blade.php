@php
    $locale = app()->getLocale();
    $isEn = $locale === 'en';
    $altLocaleUrl = $isEn ? '/mentions-legales' : '/en/legal';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" class="dark">
<head>
    <script>
        if (localStorage.getItem('md2spip-theme') === 'light') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.legal.page_title') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .protected-email { font-size: 0; }
        .protected-email::before {
            font-size: 1.125rem;
            content: attr(data-email-user) "@" attr(data-email-domain);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-900 min-h-screen text-gray-900 dark:text-slate-100 transition-colors"
    x-data="{
        darkMode: localStorage.getItem('md2spip-theme') !== 'light',
        init() { this.updateTheme(); },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            this.updateTheme();
        },
        updateTheme() {
            localStorage.setItem('md2spip-theme', this.darkMode ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.darkMode);
        }
    }">
    @php
        $email = (string) (config('legal.contact_email') ?? config('mail.from.address') ?? '');
        [$emailUser, $emailDomain] = $email && str_contains($email, '@')
            ? explode('@', $email, 2)
            : [null, null];
        $website = (string) (config('legal.social.website') ?? '');
        $homeUrl = $locale === 'en' ? '/en' : '/';
    @endphp
    <div class="max-w-4xl mx-auto px-6 py-16">
        <header class="mb-16">
            <div class="flex items-center justify-between mb-8">
                <a href="{{ $homeUrl }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-900 dark:text-white rounded-lg transition-colors border border-gray-300 dark:border-slate-700">
                    <x-icon.arrow-left />
                    <span class="text-sm font-medium">{{ __('messages.legal.back') }}</span>
                </a>

                <div class="flex items-center gap-3">
                <a href="{{ $altLocaleUrl }}" class="text-xs font-semibold uppercase tracking-wide px-2 py-1 rounded bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900" aria-label="{{ __('messages.switcher.aria') }}" hreflang="{{ $isEn ? 'fr' : 'en' }}">{{ $isEn ? 'FR' : 'EN' }}</a>

                <button
                    @click="toggleTheme()"
                    class="cursor-pointer w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                    :title="darkMode ? @js(__('messages.theme.switch_to_light_title')) : @js(__('messages.theme.switch_to_dark_title'))"
                    :aria-label="darkMode ? @js(__('messages.theme.switch_to_light_aria')) : @js(__('messages.theme.switch_to_dark_aria'))"
                >
                    <x-icon.sun x-show="darkMode" style="display: none;" />
                    <x-icon.moon x-show="!darkMode" style="display: none;" />
                </button>
                </div>
            </div>
            <h1 class="text-5xl font-bold text-gray-900 dark:text-white">{{ __('messages.legal.h1') }}</h1>
        </header>

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
    @livewireScripts
</body>
</html>
