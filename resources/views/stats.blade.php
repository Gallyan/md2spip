@php
    $isEn = \App\Support\LocaleUrls::isEnglish();
    $thousands = $isEn ? ',' : ' ';
    $decimal = $isEn ? '.' : ',';
    $dateFormat = $isEn ? 'm/d' : 'd/m';
@endphp
<x-layouts.app :title="__('messages.stats.page_title')" robots="noindex, nofollow">
    <div class="max-w-5xl mx-auto px-6 py-12">
        <x-page-header :back="__('messages.stats.back')" :title="__('messages.stats.h1')" />

        {{-- KPI cards --}}
        <section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12" aria-label="{{ __('messages.stats.chart_kpi_aria') }}">
            @php
                $humanize = function (int $value) use ($decimal): string {
                    $format = fn (float $v, string $unit): string => rtrim(rtrim(number_format($v, 1, $decimal, ''), '0'), $decimal).$unit;
                    if ($value < 1000) {
                        return (string) $value;
                    }
                    if ($value < 1_000_000) {
                        return $format($value / 1000, 'k');
                    }
                    if ($value < 1_000_000_000) {
                        return $format($value / 1_000_000, 'M');
                    }

                    return $format($value / 1_000_000_000, 'G');
                };
                $kpis = [
                    ['value' => number_format($totals['sessions'], 0, $decimal, $thousands), 'sub' => __('messages.stats.kpi_visits')],
                    ['value' => number_format($totals['conversions'], 0, $decimal, $thousands), 'sub' => __('messages.stats.kpi_conversions')],
                    ['value' => number_format($totals['copies'], 0, $decimal, $thousands), 'sub' => __('messages.stats.kpi_copies')],
                    ['value' => $humanize($totals['total_chars']), 'sub' => __('messages.stats.kpi_chars')],
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
        <section class="bg-white dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 rounded-xl p-6 mb-8" aria-label="{{ __('messages.stats.chart_activity_aria') }}">
            <div class="flex items-baseline justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ __('messages.stats.chart_title') }}</h2>
                <div class="flex flex-wrap items-center gap-4 text-xs">
                    <span class="inline-flex items-center gap-2 text-gray-600 dark:text-slate-300">
                        <span class="inline-block w-4 h-0.5 bg-slate-400 dark:bg-slate-500"></span> {{ __('messages.stats.chart_legend_visits') }}
                    </span>
                    <span class="inline-flex items-center gap-2 text-gray-600 dark:text-slate-300">
                        <span class="inline-block w-4 h-0.5 bg-emerald-500"></span> {{ __('messages.stats.chart_legend_conversions') }}
                    </span>
                    <span class="inline-flex items-center gap-2 text-gray-600 dark:text-slate-300">
                        <span class="inline-block w-4 h-0.5 bg-sky-500"></span> {{ __('messages.stats.chart_legend_copies') }}
                    </span>
                </div>
            </div>

            @if (empty($daily))
                <p class="text-center text-gray-500 dark:text-slate-400 py-12">{{ __('messages.stats.empty') }}</p>
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
                        ['key' => 'sessions', 'label' => $isEn ? 'visit' : 'visite', 'class' => 'stroke-slate-400 dark:stroke-slate-500'],
                        ['key' => 'conversions', 'label' => $isEn ? 'conversion' : 'conversion', 'class' => 'stroke-emerald-500'],
                        ['key' => 'copies', 'label' => $isEn ? 'copy' : 'copie', 'class' => 'stroke-sky-500'],
                    ];
                @endphp
                <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight + $padBottom }}" class="w-full h-auto" role="img" aria-label="{{ __('messages.stats.chart_label_aria') }}">
                    @for ($t = 0; $t <= $tickCount; $t++)
                        @php
                            $value = (int) round($niceMax * $t / $tickCount);
                            $y = $chartHeight - ($t / $tickCount) * ($chartHeight - $padTop);
                        @endphp
                        <line x1="{{ $padLeft }}" y1="{{ round($y, 2) }}" x2="{{ $chartWidth }}" y2="{{ round($y, 2) }}" stroke="currentColor" stroke-opacity="{{ $t === 0 ? '0.2' : '0.08' }}" stroke-width="1" />
                        <text x="{{ $padLeft - 8 }}" y="{{ round($y + 4, 2) }}" text-anchor="end" class="fill-gray-600 dark:fill-slate-300" font-size="12">{{ number_format($value, 0, $decimal, $thousands) }}</text>
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
                        <text x="{{ round($padLeft + $i * $stepX, 2) }}" y="{{ $chartHeight + 22 }}" text-anchor="middle" class="fill-gray-600 dark:fill-slate-300" font-size="14">{{ \Carbon\Carbon::parse($daily[$i]['date'])->format($dateFormat) }}</text>
                    @endforeach
                </svg>
            @endif
        </section>

        <footer class="text-center text-xs text-gray-500 dark:text-slate-400 pt-8 border-t border-gray-300 dark:border-slate-700">
            <p>{{ __('messages.stats.footer_note') }}</p>
        </footer>
    </div>
</x-layouts.app>
