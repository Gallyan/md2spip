<?php

declare(strict_types=1);

use App\Http\Controllers\ContactRedirectController;
use App\Http\Controllers\StatsController;
use App\Livewire\MarkdownToSpipPage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// French (default locale)
Route::middleware('setlocale:fr')->group(function (): void {
    Route::get('/', MarkdownToSpipPage::class)->name('home');
    Route::get('/guide', fn () => view('guide'))->name('guide');
    Route::get('/mentions-legales', fn () => view('mentions-legales'))->name('legal');
    Route::get('/stats', StatsController::class)->name('stats');
    Route::get('/contact', ContactRedirectController::class)->name('contact');
});

// English
Route::middleware('setlocale:en')->prefix('en')->name('en.')->group(function (): void {
    Route::get('/', MarkdownToSpipPage::class)->name('home');
    Route::get('/guide', fn () => view('guide'))->name('guide');
    Route::get('/legal', fn () => view('mentions-legales'))->name('legal');
    Route::get('/stats', StatsController::class)->name('stats');
    Route::get('/contact', ContactRedirectController::class)->name('contact');
});

Route::get('/sitemap.xml', function () {
    $lastmod = file_exists(base_path('VERSION'))
        ? trim((string) (file(base_path('VERSION'))[1] ?? ''))
        : now()->toIso8601String();

    $pages = [
        ['fr' => url('/'), 'en' => url('/en'), 'changefreq' => 'weekly', 'priority' => ['1.0', '0.9']],
        ['fr' => url('/guide'), 'en' => url('/en/guide'), 'changefreq' => 'monthly', 'priority' => ['0.8', '0.7']],
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
        .'<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>'."\n"
        .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

    foreach ($pages as $page) {
        foreach (['fr', 'en'] as $i => $locale) {
            $xml .= '  <url>'."\n"
                .'    <loc>'.$page[$locale].'</loc>'."\n"
                .'    <lastmod>'.e($lastmod).'</lastmod>'."\n"
                .'    <changefreq>'.$page['changefreq'].'</changefreq>'."\n"
                .'    <priority>'.$page['priority'][$i].'</priority>'."\n"
                .'    <xhtml:link rel="alternate" hreflang="fr" href="'.$page['fr'].'"/>'."\n"
                .'    <xhtml:link rel="alternate" hreflang="en" href="'.$page['en'].'"/>'."\n"
                .'    <xhtml:link rel="alternate" hreflang="x-default" href="'.$page['fr'].'"/>'."\n"
                .'  </url>'."\n";
        }
    }

    $xml .= '</urlset>'."\n";

    return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
});

Route::get('/robots.txt', function () {
    $body = "User-agent: *\n"
        ."Disallow: /mentions-legales\n"
        ."Disallow: /stats\n"
        ."Disallow: /contact\n"
        ."Disallow: /en/legal\n"
        ."Disallow: /en/stats\n"
        ."Disallow: /en/contact\n"
        ."\n"
        .'Sitemap: '.url('/sitemap.xml')."\n";

    return Response::make($body, 200, ['Content-Type' => 'text/plain']);
});
