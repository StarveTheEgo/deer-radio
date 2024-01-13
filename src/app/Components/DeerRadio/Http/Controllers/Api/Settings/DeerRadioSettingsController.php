<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Http\Controllers\Api\Settings;

use App\Components\Setting\Service\SettingReadService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class DeerRadioSettingsController extends Controller
{
    private ResponseFactory $responseFactory;

    private SettingReadService $settingReadService;

    /**
     * @param ResponseFactory $responseFactory
     * @param SettingReadService $settingReadService
     */
    public function __construct(
        ResponseFactory $responseFactory,
        SettingReadService $settingReadService,
    )
    {
        $this->responseFactory = $responseFactory;
        $this->settingReadService = $settingReadService;
    }

    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        $settingReadService = $this->settingReadService;

        return $this->responseFactory->json([

        ]);
    }
}
