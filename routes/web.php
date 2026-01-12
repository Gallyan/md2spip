<?php

use App\Livewire\MarkdownToSpipPage;
use Illuminate\Support\Facades\Route;

Route::get('/', MarkdownToSpipPage::class);
Route::get('/mentions-legales', fn() => view('mentions-legales'));

// Redirection email obfusquée (protection anti-spam)
Route::get('/contact-email', function () {
    $email = config('app.contact_email', 'contact@example.com');
    return redirect()->away("mailto:{$email}?subject=Contact")
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
});
