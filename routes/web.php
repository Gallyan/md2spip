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
    Route::get('/mentions-legales', fn () => view('mentions-legales'))->name('legal');
    Route::get('/stats', StatsController::class)->name('stats');
    Route::get('/contact', ContactRedirectController::class)->name('contact');
});

// English
Route::middleware('setlocale:en')->prefix('en')->name('en.')->group(function (): void {
    Route::get('/', MarkdownToSpipPage::class)->name('home');
    Route::get('/legal', fn () => view('mentions-legales'))->name('legal');
    Route::get('/stats', StatsController::class)->name('stats');
    Route::get('/contact', ContactRedirectController::class)->name('contact');
});

Route::get('/sitemap.xml', function () {
    $lastmod = file_exists(base_path('VERSION'))
        ? trim((string) (file(base_path('VERSION'))[1] ?? ''))
        : now()->toIso8601String();

    $homeFr = url('/');
    $homeEn = url('/en');

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
        .'<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>'."\n"
        .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n"
        .'  <url>'."\n"
        .'    <loc>'.$homeFr.'</loc>'."\n"
        .'    <lastmod>'.e($lastmod).'</lastmod>'."\n"
        .'    <changefreq>weekly</changefreq>'."\n"
        .'    <priority>1.0</priority>'."\n"
        .'    <xhtml:link rel="alternate" hreflang="fr" href="'.$homeFr.'"/>'."\n"
        .'    <xhtml:link rel="alternate" hreflang="en" href="'.$homeEn.'"/>'."\n"
        .'    <xhtml:link rel="alternate" hreflang="x-default" href="'.$homeFr.'"/>'."\n"
        .'  </url>'."\n"
        .'  <url>'."\n"
        .'    <loc>'.$homeEn.'</loc>'."\n"
        .'    <lastmod>'.e($lastmod).'</lastmod>'."\n"
        .'    <changefreq>weekly</changefreq>'."\n"
        .'    <priority>0.9</priority>'."\n"
        .'    <xhtml:link rel="alternate" hreflang="fr" href="'.$homeFr.'"/>'."\n"
        .'    <xhtml:link rel="alternate" hreflang="en" href="'.$homeEn.'"/>'."\n"
        .'    <xhtml:link rel="alternate" hreflang="x-default" href="'.$homeFr.'"/>'."\n"
        .'  </url>'."\n"
        .'</urlset>'."\n";

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
