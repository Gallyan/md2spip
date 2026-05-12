<?php

declare(strict_types=1);

use App\Http\Controllers\ContactRedirectController;
use App\Http\Controllers\StatsController;
use App\Livewire\MarkdownToSpipPage;
use Illuminate\Support\Facades\Route;

Route::get('/', MarkdownToSpipPage::class);
Route::get('/mentions-legales', fn () => view('mentions-legales'));
Route::get('/stats', StatsController::class);

// Redirection email obfusquée (protection anti-spam)
Route::get('/contact-email', ContactRedirectController::class);
