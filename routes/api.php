<?php

use App\Http\Controllers\API\StatsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('stats/contributions', [StatsController::class, 'contributionCalendar']);
});
