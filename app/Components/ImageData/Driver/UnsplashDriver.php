<?php

declare(strict_types=1);

namespace App\Components\ImageData\Driver;

use App\Components\ImageData\ImageData;
use App\Components\ImageData\UnsplashImageDataFactory;
use App\Components\UnsplashClient\UnsplashClient;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;

class UnsplashDriver implements ImageDataListProviderInterface
{
    public const SETTING_IS_ENABLED = 'unsplash-query.is_enabled';

    private UnsplashClient $unsplashClient;
    private UnsplashImageDataFactory $unsplashImageDataFactory;
    private UnsplashSearchQueryBuilderInterface $queryBuilder;

    public static function getName(): string
    {
        return 'unsplash';
    }

    public function __construct(
        UnsplashClient                      $unsplashClient,
        UnsplashSearchQueryBuilderInterface $unsplashQueryBuilder,
        UnsplashImageDataFactory            $unsplashImageDataFactory
    )
    {
        $this->unsplashClient = $unsplashClient;
        $this->unsplashImageDataFactory = $unsplashImageDataFactory;
        $this->queryBuilder = $unsplashQueryBuilder;
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
            $imageDataList[] = $this->unsplashImageDataFactory->buildImageData($unsplashImageInfo);
        }

        return $imageDataList;
    }
}