<?php

use App\Livewire\MarkdownToSpipPage;
use Illuminate\Support\Facades\Route;

Route::get('/', MarkdownToSpipPage::class);
Route::get('/mentions-legales', fn() => view('mentions-legales'));
