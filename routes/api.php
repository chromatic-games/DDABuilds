<?php

use App\Http\Controllers\BugReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('bug-report/{id}')->group(function() {
	Route::get('/close', [BugReportController::class, 'close']);
});

Route::get('/user/{username}', function (Request $request) {
    return [1,2,3];
});