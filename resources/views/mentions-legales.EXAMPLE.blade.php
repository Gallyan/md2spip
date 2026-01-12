<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions légales - Markdown to SPIP</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css'])
    <style>
        /* Obfuscation CSS de l'email contre les robots spammeurs */
        .protected-email {
            font-size: 0;
        }
        .protected-email::before {
            font-size: 1.125rem;
            content: attr(data-email-user) "@" attr(data-email-domain);
        }
    </style>
</head>
<body class="bg-slate-900 min-h-screen text-slate-100">
    <div class="max-w-4xl mx-auto px-6 py-16">
        <header class="mb-16">
            <a href="/" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg transition-colors mb-8 border border-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span class="text-sm font-medium">Retour au convertisseur</span>
            </a>
            <h1 class="text-5xl font-bold text-white">Mentions légales</h1>
        </header>

        <div class="space-y-12 leading-relaxed">
            <section class="bg-slate-800/50 rounded-xl p-8 border border-slate-700">
                <h2 class="text-2xl font-semibold text-white mb-5">Éditeur du site</h2>
                <p class="text-slate-200 text-lg">
                    <strong class="text-white">Votre Nom / Raison sociale</strong>, statut juridique (EI, SARL, SAS, etc.)<br>
                    SIRET : XXX XXX XXX XXXXX<br>
                    N° TVA : FRXXXXXXXXXX (si applicable)<br>
                    Adresse complète : numéro, rue, code postal, ville<br>
                    Tél : +33 X XX XX XX XX<br>
                    E-mail : <a href="/contact-email" class="hover:text-emerald-300 transition-colors"><span class="protected-email text-emerald-400" data-email-user="votre-identifiant" data-email-domain="votre-example.com">[email protected]</span></a><br>
                    Site web : <a href="https://votre-example.com" target="_blank" rel="noopener" class="text-emerald-400 hover:text-emerald-300 underline">votre-example.com</a>
                </p>
            </section>

            <section class="bg-slate-800/50 rounded-xl p-8 border border-slate-700">
                <h2 class="text-2xl font-semibold text-white mb-5">Hébergement</h2>
                <p class="text-slate-200 text-lg">
                    Ce site est hébergé par :<br>
                    <strong class="text-white">Nom de l'hébergeur</strong><br>
                    Adresse complète : numéro, rue, code postal, ville, pays<br>
                    Tél : numéro de téléphone<br>
                    SIRET : XXX XXX XXX XXXXX (si applicable)
                </p>
            </section>

            <section class="bg-slate-800/50 rounded-xl p-8 border border-slate-700">
                <h2 class="text-2xl font-semibold text-white mb-5">Logiciel libre</h2>
                <p class="text-slate-200 text-lg">Cette application est un logiciel libre distribué sous licence <strong class="text-white">GNU GPL-3.0</strong>.</p>
                <p class="mt-4 text-slate-200 text-lg">
                    Vous êtes libre de l'utiliser, le modifier et le redistribuer selon les termes de cette licence.<br>
                    Code source disponible sur : <a href="https://github.com/Gallyan/md2spip" target="_blank" rel="noopener" class="text-emerald-400 hover:text-emerald-300 underline">github.com/Gallyan/md2spip</a>
                </p>
                <p class="mt-4 text-slate-200 text-lg">
                    Projet créé par <a href="https://www.orsal.fr" target="_blank" rel="noopener" class="text-emerald-400 hover:text-emerald-300 underline font-semibold">Guillaume Orsal</a>
                </p>
            </section>

            <section class="bg-slate-800/50 rounded-xl p-8 border border-slate-700">
                <h2 class="text-2xl font-semibold text-white mb-5">Données personnelles</h2>
                <p class="text-slate-200 text-lg mb-4">Ce site ne collecte <strong class="text-white">aucune donnée personnelle</strong>.</p>
                <ul class="list-disc list-inside space-y-2 ml-4 text-slate-200 text-lg">
                    <li>Aucun cookie de tracking</li>
                    <li>Aucune analyse de trafic</li>
                    <li>Aucun stockage des conversions Markdown/SPIP</li>
                    <li>Aucun compte utilisateur</li>
                </ul>
                <div class="mt-6 p-4 bg-slate-900/50 rounded-lg border border-slate-600">
                    <p class="text-slate-200 text-lg"><strong class="text-white">Version en ligne :</strong> Le texte que vous convertissez est transmis au serveur pour effectuer la conversion en temps réel, mais <strong class="text-white">il n'est jamais stocké</strong>. Aucune trace de vos conversions n'est conservée.</p>
                    <p class="mt-3 text-slate-200 text-lg"><strong class="text-white">Version locale :</strong> Si vous installez l'application localement, toutes les conversions restent sur votre machine.</p>
                </div>
            </section>

            <section class="bg-slate-800/50 rounded-xl p-8 border border-slate-700">
                <h2 class="text-2xl font-semibold text-white mb-5">Cookies</h2>
                <p class="text-slate-200 text-lg">Ce site utilise uniquement des cookies techniques essentiels au fonctionnement de l'application (session Laravel, protection CSRF). Aucun cookie de suivi publicitaire ou analytique n'est déposé.</p>
                <p class="mt-4 text-slate-200 text-lg">Conformément aux recommandations de la CNIL, <strong class="text-white">ce type de cookie est dispensé du recueil de consentement</strong> car il est strictement nécessaire à la fourniture du service.</p>
            </section>

            <section class="bg-slate-800/50 rounded-xl p-8 border border-slate-700">
                <h2 class="text-2xl font-semibold text-white mb-5">Responsabilité</h2>
                <p class="text-slate-200 text-lg">L'éditeur s'efforce d'assurer la précision et la fiabilité des conversions Markdown vers SPIP, mais ne peut garantir l'exactitude absolue des résultats.</p>
                <p class="mt-4 text-slate-200 text-lg">L'utilisateur reste responsable de la vérification du code SPIP généré avant utilisation en production.</p>
            </section>

            <section class="text-sm text-slate-400 pt-8 border-t border-slate-700">
                <p>Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </section>
        </div>
    </div>
</body>
</html>
