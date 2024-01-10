<?php

declare(strict_types=1);

namespace App\Components\ImageData\Driver;

use App\Components\ImageData\Enum\UnsplashDriverSettingKey;
use App\Components\ImageData\ImageData;
use App\Components\ImageData\UnsplashImageDataFactory;
use App\Components\Setting\Service\SettingReadService;
use App\Components\UnsplashClient\UnsplashClient;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;

class UnsplashDriver implements ImageDataListProviderInterface
{
    public const SETTING_DOWNLOAD_QUERY_PARAMS = 'unsplash-query.download_query_params';

    private UnsplashClient $unsplashClient;
    private UnsplashImageDataFactory $unsplashImageDataFactory;
    private UnsplashSearchQueryBuilderInterface $queryBuilder;
    private SettingReadService $settingReadService;

    public static function getName(): string
    {
        return 'unsplash';
    }

    public function __construct(
        UnsplashClient                      $unsplashClient,
        UnsplashSearchQueryBuilderInterface $unsplashQueryBuilder,
        UnsplashImageDataFactory            $unsplashImageDataFactory,
        SettingReadService $settingReadService,
    )
    {
        $this->unsplashClient = $unsplashClient;
        $this->unsplashImageDataFactory = $unsplashImageDataFactory;
        $this->queryBuilder = $unsplashQueryBuilder;
        $this->settingReadService = $settingReadService;
    }

    /**
     * @inheritDoc
     * @return ImageData[]
     */
    public function getImageDataList(): array
    {
        $photoListResponse = $this->unsplashClient->runSearchQuery(
            $this->queryBuilder->buildSearchQuery()
        );

        $imageDataList = [];
        foreach ($photoListResponse as $unsplashImageInfo) {
            $imageData = $this->unsplashImageDataFactory->buildImageData($unsplashImageInfo);

            // additional download query params
            $downloadQueryParams = $this->settingReadService->getValue(UnsplashDriverSettingKey::DOWNLOAD_QUERY_PARAMS->value);
            if ($downloadQueryParams !== null) {
                $imageData->setPath($imageData->getPath().$downloadQueryParams);
            }

            $imageDataList[] = $imageData;
        }

        return $imageDataList;
    }
}
