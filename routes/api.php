<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ConvertController;
use Illuminate\Support\Facades\Route;

Route::post('/convert', ConvertController::class)->name('api.convert');
