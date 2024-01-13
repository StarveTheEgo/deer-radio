<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class DeerLivestreamChatController extends Controller
{
    private ResponseFactory $responseFactory;

    /**
     * @param ResponseFactory $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function sendMessage(string $message) : JsonResponse
    {
        // @todo implement
        return $this->responseFactory->json([
            'message' => 'message',
        ]);
    }
}
