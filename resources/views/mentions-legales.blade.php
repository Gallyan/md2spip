<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <script>
        if (localStorage.getItem('md2spip-theme') === 'light') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions légales - Markdown to SPIP</title>
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
    @endphp
    <div class="max-w-4xl mx-auto px-6 py-16">
        <header class="mb-16">
            <div class="flex items-center justify-between mb-8">
                <a href="/" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-900 dark:text-white rounded-lg transition-colors border border-gray-300 dark:border-slate-700">
                    <x-icon.arrow-left />
                    <span class="text-sm font-medium">Retour au convertisseur</span>
                </a>

                <button
                    @click="toggleTheme()"
                    class="cursor-pointer w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                    :title="darkMode ? 'Passer en mode clair' : 'Passer en mode sombre'"
                    :aria-label="darkMode ? 'Activer le mode clair' : 'Activer le mode sombre'"
                >
                    <x-icon.sun x-show="darkMode" style="display: none;" />
                    <x-icon.moon x-show="!darkMode" style="display: none;" />
                </button>
            </div>
            <h1 class="text-5xl font-bold text-gray-900 dark:text-white">Mentions légales</h1>
        </header>

        <div class="space-y-12 leading-relaxed">
            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">Éditeur du site</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg">
                    <strong class="text-gray-900 dark:text-white">{{ config('legal.editor_name') }}</strong><br>
                    @if (config('legal.editor_address'))
                        {{ config('legal.editor_address') }}<br>
                    @endif
                    @if (config('legal.editor_phone'))
                        Tél : {{ config('legal.editor_phone') }}<br>
                    @endif
                    @if ($emailUser && $emailDomain)
                        E-mail : <a href="/contact" class="hover:text-emerald-600 dark:hover:text-emerald-300 transition-colors"><span class="protected-email text-emerald-600 dark:text-emerald-400" data-email-user="{{ $emailUser }}" data-email-domain="{{ $emailDomain }}">[email protected]</span></a><br>
                    @endif
                    @if ($website)
                        Site web : <a href="{{ $website }}" target="_blank" rel="noopener" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 underline">{{ preg_replace('#^https?://#', '', $website) }}</a>
                    @endif
                </p>
            </section>

            @if (config('legal.hosting.name'))
                <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">Hébergement</h2>
                    <p class="text-gray-700 dark:text-slate-200 text-lg">
                        Ce site est hébergé par :<br>
                        <strong class="text-gray-900 dark:text-white">{{ config('legal.hosting.name') }}</strong><br>
                        @if (config('legal.hosting.address'))
                            {{ config('legal.hosting.address') }}<br>
                        @endif
                        @if (config('legal.hosting.phone'))
                            Tél : {{ config('legal.hosting.phone') }}
                        @endif
                    </p>
                </section>
            @endif

            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">Logiciel libre</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg">Cette application est un logiciel libre distribué sous licence <strong class="text-gray-900 dark:text-white">GNU GPL-3.0</strong>.</p>
                <p class="mt-4 text-gray-700 dark:text-slate-200 text-lg">
                    Vous êtes libre de l'utiliser, le modifier et le redistribuer selon les termes de cette licence.<br>
                    Code source disponible sur : <a href="https://github.com/Gallyan/md2spip" target="_blank" rel="noopener" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 underline">github.com/Gallyan/md2spip</a>
                </p>
            </section>

            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">Données personnelles</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg mb-4">Ce site ne collecte <strong class="text-gray-900 dark:text-white">aucune donnée personnelle</strong>.</p>
                <ul class="list-disc list-inside space-y-2 ml-4 text-gray-700 dark:text-slate-200 text-lg">
                    <li>Aucun cookie de tracking</li>
                    <li>Aucune analyse de trafic</li>
                    <li>Aucun stockage des conversions Markdown/SPIP</li>
                    <li>Aucun compte utilisateur</li>
                </ul>
                <div class="mt-6 p-4 bg-gray-100 dark:bg-slate-900/50 rounded-lg border border-gray-300 dark:border-slate-600 transition-colors">
                    <p class="text-gray-700 dark:text-slate-200 text-lg"><strong class="text-gray-900 dark:text-white">Version en ligne :</strong> Le texte que vous convertissez est transmis au serveur pour effectuer la conversion en temps réel, mais <strong class="text-gray-900 dark:text-white">il n'est jamais stocké</strong>. Aucune trace de vos conversions n'est conservée.</p>
                    <p class="mt-3 text-gray-700 dark:text-slate-200 text-lg"><strong class="text-gray-900 dark:text-white">Version locale :</strong> Si vous installez l'application localement, toutes les conversions restent sur votre machine.</p>
                </div>
            </section>

            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">Cookies</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg">Ce site utilise uniquement des cookies techniques essentiels au fonctionnement de l'application (session Laravel, protection CSRF). Aucun cookie de suivi publicitaire ou analytique n'est déposé.</p>
                <p class="mt-4 text-gray-700 dark:text-slate-200 text-lg">Conformément aux recommandations de la CNIL, <strong class="text-gray-900 dark:text-white">ce type de cookie est dispensé du recueil de consentement</strong> car il est strictement nécessaire à la fourniture du service.</p>
            </section>

            <section class="bg-white dark:bg-slate-800/50 rounded-xl p-8 border border-gray-200 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5">Responsabilité</h2>
                <p class="text-gray-700 dark:text-slate-200 text-lg">L'éditeur s'efforce d'assurer la précision et la fiabilité des conversions Markdown vers SPIP, mais ne peut garantir l'exactitude absolue des résultats.</p>
                <p class="mt-4 text-gray-700 dark:text-slate-200 text-lg">L'utilisateur reste responsable de la vérification du code SPIP généré avant utilisation en production.</p>
            </section>

            <section class="text-sm text-gray-600 dark:text-slate-400 pt-8 border-t border-gray-300 dark:border-slate-700">
                <p>Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </section>
        </div>
    </div>
    @livewireScripts
</body>
</html>
