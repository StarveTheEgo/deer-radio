<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Liquidsoap;

use App\Components\DeerRadio\Commands\DeerImageUpdate;
use App\Components\DeerRadio\Commands\GetCurrentDeerImage;
use App\Components\DeerRadio\Commands\GetNextSong;
use App\Components\DeerRadio\Commands\UpdateNowPlayingId;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CliCommandController extends Controller
{
    /**
     * Runs specified command and returns the output as string
     * @param class-string $commandClassName
     * @param array $parameters
     * @return string
     */
    private function runCommand(string $commandClassName, array $parameters): string
    {
        Artisan::call($commandClassName, $parameters);

        return rtrim(Artisan::output());
    }

    /**
     * @return JsonResponse
     */
    public function getCurrentDeerImage(): JsonResponse
    {
        $result = $this->runCommand(GetCurrentDeerImage::class, []);

        return response()->json([
            'result' => $result,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function updateDeerImage(): JsonResponse
    {
        $result = $this->runCommand(DeerImageUpdate::class, []);

        return response()->json([
            'result' => $result,
        ]);
    }

    /**
     * @param string|null $mode
     * @return JsonResponse
     */
    public function getNextSong(?string $mode = null): JsonResponse
    {
        $result = $this->runCommand(GetNextSong::class, [
            'mode' => ($mode === 'force'),
        ]);

        return response()->json([
            'result' => $result,
        ]);
    }

    /**
     * @param int $songId
     * @return JsonResponse
     */
    public function updateNowPlaying(int $songId): JsonResponse
    {
        $result = $this->runCommand(UpdateNowPlayingId::class, [
            'songId' => $songId,
        ]);

        return response()->json([
            'result' => $result,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function notifyStreamChat(Request $request): JsonResponse
    {
        $message = $request->json('message');
//      @todo implement

        return response()->json([
            'result' => 'Stub message: '.$message,
        ]);
    }
}
