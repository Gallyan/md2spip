<?php

declare(strict_types=1);

use App\Http\Controllers\ContactRedirectController;
use App\Http\Controllers\StatsController;
use App\Livewire\MarkdownToSpipPage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', MarkdownToSpipPage::class);
Route::get('/mentions-legales', fn () => view('mentions-legales'));
Route::get('/stats', StatsController::class);

// Redirection email obfusquée (protection anti-spam)
Route::get('/contact', ContactRedirectController::class);

Route::get('/sitemap.xml', function () {
    $lastmod = file_exists(base_path('VERSION'))
        ? trim((string) (file(base_path('VERSION'))[1] ?? ''))
        : now()->toIso8601String();

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
        .'<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>'."\n"
        .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
        .'  <url>'."\n"
        .'    <loc>'.url('/').'</loc>'."\n"
        .'    <lastmod>'.e($lastmod).'</lastmod>'."\n"
        .'    <changefreq>weekly</changefreq>'."\n"
        .'    <priority>1.0</priority>'."\n"
        .'  </url>'."\n"
        .'</urlset>'."\n";

    return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
});

Route::get('/robots.txt', function () {
    $body = "User-agent: *\n"
        ."Disallow: /mentions-legales\n"
        ."Disallow: /stats\n"
        ."Disallow: /contact\n"
        ."\n"
        .'Sitemap: '.url('/sitemap.xml')."\n";

    return Response::make($body, 200, ['Content-Type' => 'text/plain']);
});
