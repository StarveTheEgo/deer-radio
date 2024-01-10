<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Liquidsoap\CliCommandController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(CliCommandController::class)
    ->prefix('liquidsoap')
    ->group(function () {
        Route::get('/current-deer-image', 'getCurrentDeerImage');
        Route::get('/update-deer-image', 'updateDeerImage');

        Route::get('/next-song/{mode?}', 'getNextSong');
        Route::get('/update-now-playing/{songId}', 'updateNowPlaying');

        Route::get('/notify-stream-chat', 'notifyStreamChat');
    });
