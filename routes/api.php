<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BugReportController;
use App\Http\Controllers\BuildController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

// resource api routes
Route::resources([
	'bug-reports' => BugReportController::class,
	'builds'      => BuildController::class,
]);

// routes where an authentication is not required
Route::group(['middleware' => ['auth:guest']], function () {
	Route::get('/auth/steam', [AuthController::class, 'auth']);
});

// routes where an authentication is required
Route::group(['middleware' => ['auth:user']], function () {
	Route::get('/auth', [AuthController::class, 'authInfo']);
	Route::delete('/auth', [AuthController::class, 'logout']);
});

// every other route -> not found
Route::get('{any}', function () {
	return response()->apiResponse('', SymfonyResponse::HTTP_NOT_FOUND);
})->where('any', '.*?');