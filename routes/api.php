<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\IssueCommentController;
use App\Http\Controllers\IssueController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// resource api routes
Route::apiResources([
	'builds' => BuildController::class,
]);

// routes where an authentication is not required
Route::group(['middleware' => ['auth:guest']], function () {
	Route::get('/auth/steam', [AuthController::class, 'auth']);
});

// routes where an authentication is required
Route::group(['middleware' => ['auth:user']], function () {
	Route::get('/auth', [AuthController::class, 'authInfo']);
	Route::delete('/auth', [AuthController::class, 'logout']);

	Route::apiResources([
		'issues' => IssueController::class,
		'issues.comments' => IssueCommentController::class,
	]);
});

// every other route -> not found
Route::get('{any}', function () {
	throw new NotFoundHttpException();
})->where('any', '.*?');