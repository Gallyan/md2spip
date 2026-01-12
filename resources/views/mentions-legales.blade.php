<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions légales - Markdown to SPIP</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-900 min-h-screen text-slate-100">
    <div class="max-w-4xl mx-auto px-6 py-12">
        <header class="mb-12">
            <a href="/" class="inline-flex items-center gap-3 text-white hover:text-emerald-400 transition-colors mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Retour</span>
            </a>
            <h1 class="text-4xl font-bold text-white">Mentions légales</h1>
        </header>

        <div class="space-y-8 leading-relaxed">
            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">Éditeur du site</h2>
                <p class="text-slate-200">Guillaume Orsal<br>
                Site web : <a href="https://md2spip.orsal.net" class="text-emerald-400 hover:underline">md2spip.orsal.net</a></p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">Hébergement</h2>
                <p class="text-slate-200">Ce site est hébergé par :<br>
                <strong>OVH SAS</strong><br>
                2 rue Kellermann - 59100 Roubaix - France</p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">Propriété intellectuelle</h2>
                <p class="text-slate-200">L'ensemble du contenu de ce site (code source, interface, textes) est la propriété exclusive de Guillaume Orsal, sauf mention contraire.</p>
                <p class="mt-3 text-slate-200">Toute reproduction, distribution, modification ou exploitation sans autorisation préalable est interdite.</p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">Données personnelles</h2>
                <p class="text-slate-200">Ce site ne collecte <strong class="text-white">aucune donnée personnelle</strong>.</p>
                <ul class="list-disc list-inside mt-3 space-y-2 ml-4 text-slate-200">
                    <li>Aucun cookie de tracking</li>
                    <li>Aucune analyse de trafic</li>
                    <li>Aucun stockage des conversions Markdown/SPIP</li>
                    <li>Aucun compte utilisateur</li>
                </ul>
                <p class="mt-4 text-slate-200">Le texte que vous convertissez est transmis au serveur pour effectuer la conversion en temps réel, mais <strong class="text-white">il n'est jamais stocké</strong>. Aucune trace de vos conversions n'est conservée.</p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">Cookies</h2>
                <p class="text-slate-200">Ce site utilise uniquement des cookies techniques essentiels au fonctionnement de l'application (session Laravel, protection CSRF). Aucun cookie de suivi publicitaire ou analytique n'est déposé.</p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">Responsabilité</h2>
                <p class="text-slate-200">L'éditeur s'efforce d'assurer la précision et la fiabilité des conversions Markdown vers SPIP, mais ne peut garantir l'exactitude absolue des résultats.</p>
                <p class="mt-3 text-slate-200">L'utilisateur reste responsable de la vérification du code SPIP généré avant utilisation en production.</p>
            </section>

            <section class="text-sm text-slate-400 pt-8 border-t border-slate-700">
                <p>Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </section>
        </div>
    </div>
</body>
</html>
