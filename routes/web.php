<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::any('{any}', [IndexController::class, 'index'])->where('any', '^(?!api).*$');