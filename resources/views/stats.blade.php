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
    <title>Statistiques - Markdown to SPIP</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
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
    <div class="max-w-5xl mx-auto px-6 py-12">
        <header class="mb-12">
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
            <h1 class="text-5xl font-bold text-gray-900 dark:text-white">Statistiques d'usage</h1>
        </header>

        {{-- KPI cards --}}
        <section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12" aria-label="Indicateurs clés">
            @php
                $kpis = [
                    ['value' => number_format($totals['sessions'], 0, ',', ' '), 'sub' => 'visites'],
                    ['value' => number_format($totals['conversions'], 0, ',', ' '), 'sub' => 'conversions'],
                    ['value' => number_format($totals['copies'], 0, ',', ' '), 'sub' => 'copies'],
                    ['value' => number_format($totals['total_chars'], 0, ',', ' '), 'sub' => 'caractères'],
                ];
            @endphp
            @foreach ($kpis as $kpi)
                <div class="bg-white dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 rounded-xl p-6">
                    <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ $kpi['value'] }}</p>
                    <p class="text-base text-gray-700 dark:text-slate-300">{{ $kpi['sub'] }}</p>
                </div>
            @endforeach
        </section>

        {{-- Line chart --}}
        <section class="bg-white dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 rounded-xl p-6 mb-8" aria-label="Activité quotidienne">
            <div class="flex items-baseline justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">30 derniers jours</h2>
                <div class="flex flex-wrap items-center gap-4 text-xs">
                    <span class="inline-flex items-center gap-2 text-gray-600 dark:text-slate-300">
                        <span class="inline-block w-4 h-0.5 bg-slate-400 dark:bg-slate-500"></span> Visites
                    </span>
                    <span class="inline-flex items-center gap-2 text-gray-600 dark:text-slate-300">
                        <span class="inline-block w-4 h-0.5 bg-emerald-500"></span> Conversions
                    </span>
                    <span class="inline-flex items-center gap-2 text-gray-600 dark:text-slate-300">
                        <span class="inline-block w-4 h-0.5 bg-sky-500"></span> Copies
                    </span>
                </div>
            </div>

            @if (empty($daily))
                <p class="text-center text-gray-500 dark:text-slate-400 py-12">Pas encore de données. Reviens dans quelques jours !</p>
            @else
                @php
                    $chartWidth = 800;
                    $chartHeight = 220;
                    $padBottom = 36;
                    $padTop = 10;
                    $padLeft = 32;
                    $padRight = 18;
                    $rawMax = max(
                        max(array_column($daily, 'sessions') ?: [0]),
                        max(array_column($daily, 'conversions') ?: [0]),
                        max(array_column($daily, 'copies') ?: [0]),
                        1
                    );
                    $magnitude = 10 ** floor(log10($rawMax));
                    $normalized = $rawMax / $magnitude;
                    $niceMax = (int) (($normalized <= 1 ? 1 : ($normalized <= 2 ? 2 : ($normalized <= 5 ? 5 : 10))) * $magnitude);
                    $tickCount = 4;
                    $innerWidth = $chartWidth - $padLeft - $padRight;
                    $stepX = count($daily) > 1 ? $innerWidth / (count($daily) - 1) : 0;
                    $maxLabels = 7;
                    $labelIndices = [];
                    for ($k = 0; $k < $maxLabels; $k++) {
                        $labelIndices[] = (int) round($k * (count($daily) - 1) / max(1, $maxLabels - 1));
                    }
                    $labelIndices = array_unique($labelIndices);

                    $project = function (int $value) use ($niceMax, $chartHeight, $padTop): float {
                        return $chartHeight - ($value / $niceMax) * ($chartHeight - $padTop);
                    };

                    $series = [
                        ['key' => 'sessions', 'label' => 'visite', 'class' => 'stroke-slate-400 dark:stroke-slate-500'],
                        ['key' => 'conversions', 'label' => 'conversion', 'class' => 'stroke-emerald-500'],
                        ['key' => 'copies', 'label' => 'copie', 'class' => 'stroke-sky-500'],
                    ];
                @endphp
                <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight + $padBottom }}" class="w-full h-auto" role="img" aria-label="Courbes des visites, conversions et copies sur 30 jours">
                    @for ($t = 0; $t <= $tickCount; $t++)
                        @php
                            $value = (int) round($niceMax * $t / $tickCount);
                            $y = $chartHeight - ($t / $tickCount) * ($chartHeight - $padTop);
                        @endphp
                        <line x1="{{ $padLeft }}" y1="{{ round($y, 2) }}" x2="{{ $chartWidth }}" y2="{{ round($y, 2) }}" stroke="currentColor" stroke-opacity="{{ $t === 0 ? '0.2' : '0.08' }}" stroke-width="1" />
                        <text x="{{ $padLeft - 8 }}" y="{{ round($y + 4, 2) }}" text-anchor="end" class="fill-gray-600 dark:fill-slate-300" font-size="12">{{ number_format($value, 0, ',', ' ') }}</text>
                    @endfor

                    @foreach ($series as $serie)
                        @php
                            $points = [];
                            foreach ($daily as $i => $d) {
                                $x = $padLeft + $i * $stepX;
                                $y = $project($d[$serie['key']]);
                                $points[] = round($x, 2).','.round($y, 2);
                            }
                        @endphp
                        <polyline points="{{ implode(' ', $points) }}" fill="none" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" class="{{ $serie['class'] }}" />
                        @foreach ($daily as $i => $d)
                            <circle cx="{{ round($padLeft + $i * $stepX, 2) }}" cy="{{ round($project($d[$serie['key']]), 2) }}" r="6" fill="transparent">
                                <title>{{ $d['date'] }} — {{ $d[$serie['key']] }} {{ $serie['label'] }}{{ $d[$serie['key']] > 1 ? 's' : '' }}</title>
                            </circle>
                        @endforeach
                    @endforeach

                    @foreach ($labelIndices as $i)
                        <text x="{{ round($padLeft + $i * $stepX, 2) }}" y="{{ $chartHeight + 22 }}" text-anchor="middle" class="fill-gray-600 dark:fill-slate-300" font-size="14">{{ \Carbon\Carbon::parse($daily[$i]['date'])->format('d/m') }}</text>
                    @endforeach
                </svg>
            @endif
        </section>

        <footer class="text-center text-xs text-gray-500 dark:text-slate-400 pt-8 border-t border-gray-300 dark:border-slate-700">
            <p>Aucune donnée personnelle n'est collectée. Seuls les compteurs anonymes sont stockés côté serveur.</p>
        </footer>
    </div>
    @livewireScripts
</body>
</html>
