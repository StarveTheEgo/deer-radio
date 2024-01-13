<?php

declare(strict_types=1);

use App\Components\DeerRadio\Http\Controllers\Api\Chat\DeerLivestreamChatController;
use App\Components\DeerRadio\Http\Controllers\Api\DeerImage\DeerImageIndexController;
use App\Components\DeerRadio\Http\Controllers\Api\DeerImage\DeerImageUpdateController;
use App\Components\DeerRadio\Http\Controllers\Api\DeerMusic\DeerMusicQueueController;
use App\Components\DeerRadio\Http\Controllers\Api\Settings\DeerRadioSettingsController;
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

Route::prefix('internal')
    ->group(function() {
        Route::get('settings', [DeerRadioSettingsController::class, 'index']);

        Route::get('deer-image/current', [DeerImageIndexController::class, 'index']);
        Route::get('deer-image/update', [DeerImageUpdateController::class, 'update']);

        Route::get('song-queue/enqueue/auto', [DeerMusicQueueController::class, 'enqueueNextSong']);
        Route::get('song-queue/update-current-song', [DeerMusicQueueController::class, 'updateCurrentSongId']);

        Route::get('stream-chat/send-message', [DeerLivestreamChatController::class, 'sendMessage']);
    });
