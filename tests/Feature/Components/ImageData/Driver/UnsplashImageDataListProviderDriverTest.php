<?php

declare(strict_types=1);

namespace Tests\Feature\Components\ImageData\Driver;

use App\Components\ImageData\Driver\Unsplash\UnsplashDriver;
use App\Components\ImageData\ImageData;
use App\Components\ImageData\UnsplashImageDataFactory;
use App\Components\UnsplashClient\UnsplashClient;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UnsplashImageDataListProviderDriverTest extends TestCase
{
    /**
     * @return MockObject&UnsplashClient
     */
    public function buildUnsplashClientMock() : MockObject
    {
        /** @var MockObject&UnsplashClient $mock */
        $mock = $this->createPartialMock(UnsplashClient::class, ['runSearchQuery']);

        return $mock;
    }

    /**
     * @param array<string, mixed> $searchQueryResponse
     * @param ImageData[] $expectedImageDataList
     * @return void
     *
     * @dataProvider getImageDataListDataProvider
     */
    public function testGetImageDataList(array $searchQueryResponse, array $expectedImageDataList): void
    {
        $unsplashClientMock = $this->buildUnsplashClientMock();
        $unsplashClientMock->method('runSearchQuery')->willReturn($searchQueryResponse);

        $unsplashDriver = new UnsplashDriver(
            $unsplashClientMock,
            $this->createMock(UnsplashSearchQueryBuilderInterface::class),
            new UnsplashImageDataFactory(),
        );

        $actualImageDataList = $unsplashDriver->getImageDataList();
        $this->assertCount(count($expectedImageDataList), $actualImageDataList);
        foreach ($expectedImageDataList as $index => $expectedImageData) {
            $actualImageData = $actualImageDataList[$index] ?? null;
            $this->assertNotNull($actualImageData);

            $this->assertEquals($expectedImageData->toArray(), $actualImageData->toArray());
        }
    }

    private function getImageDataListDataProvider(): array
    {
        $imageInfo1 = $this->buildUnsplashImageInfo('image1');
        $imageInfo2 = $this->buildUnsplashImageInfo('image2');
        $unsplashImageDataFactory = new UnsplashImageDataFactory();

        return [
            'emptyList' => [
                'searchQueryResponse' => [],
                'expectedImageDataList' => [],
            ],
            'twoElements' => [
                'searchQueryResponse' => [
                    $imageInfo1,
                    $imageInfo2
                ],
                'expectedImageDataList' => [
                    $unsplashImageDataFactory->buildImageData($imageInfo1),
                    $unsplashImageDataFactory->buildImageData($imageInfo2),
                ]
            ]
        ];
    }

    /**
     * Builds minimal used example of Unsplash API image info
     * @param string $imageName
     * @return array<string, mixed>
     */
    private function buildUnsplashImageInfo(string $imageName) : array {
        return [
            "description" => "Description $imageName",
            "urls" => [
                "raw" => "https://localhost/urls/html/$imageName",
            ],
            "links" => [
                "html" => "https://localhost/links/html/$imageName",
            ],
            "user" => [
                "name" => "User $imageName",
                "links" => [
                    "html" => "https://localhost/user/links/html/$imageName",
                ],
            ],
        ];
    }
}
