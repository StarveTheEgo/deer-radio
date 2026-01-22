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
    public static function getName(): string
    {
        return 'unsplash';
    }

    public function __construct(
        private readonly UnsplashClient $unsplashClient,
        private readonly UnsplashSearchQueryBuilderInterface $queryBuilder,
        private readonly UnsplashImageDataFactory $unsplashImageDataFactory,
        private readonly SettingReadService $settingReadService
    )
    {
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
