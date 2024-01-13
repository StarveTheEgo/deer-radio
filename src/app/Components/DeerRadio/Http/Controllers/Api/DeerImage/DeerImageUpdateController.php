<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Http\Controllers\Api\DeerImage;

use App\Components\DeerRadio\Service\DeerImageDeleteService;
use App\Components\DeerRadio\Service\DeerImageUpdateService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class DeerImageUpdateController extends Controller
{
    private ResponseFactory $responseFactory;

    private DeerImageUpdateService $deerImageUpdateService;

    private DeerImageDeleteService $deerImageDeleteService;

    /**
     * @param ResponseFactory $responseFactory
     * @param DeerImageUpdateService $deerImageUpdateService
     * @param DeerImageDeleteService $deerImageDeleteService
     */
    public function __construct(
        ResponseFactory $responseFactory,
        DeerImageUpdateService $deerImageUpdateService,
        DeerImageDeleteService $deerImageDeleteService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->deerImageUpdateService = $deerImageUpdateService;
        $this->deerImageDeleteService = $deerImageDeleteService;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function update(): Response
    {
        $this->deerImageDeleteService->removeOldImages();
        $this->deerImageUpdateService->update();

        return $this->responseFactory->noContent();
    }
}
