<?php

use App\Http\Controllers\BugReportController;
use App\Http\Controllers\BuildController;
use Illuminate\Support\Facades\Route;

Route::resources([
	'bug-reports' => BugReportController::class,
	'builds'      => BuildController::class,
]);

Route::get('{any}', function () {
	return [
		'error'  => 'Not Found',
		'status' => 404,
	];
})->where('any', '.*?');