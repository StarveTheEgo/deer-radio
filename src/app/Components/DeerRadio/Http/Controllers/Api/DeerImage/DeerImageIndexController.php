<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Http\Controllers\Api\DeerImage;

use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\DeerRadio\Metadata\ImageMetadataBuilder;
use App\Components\ImageData\ImageData;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeerImageIndexController extends Controller
{
    private ResponseFactory $responseFactory;

    private DeerRadioDataAccessor $dataAccessor;

    private ImageMetadataBuilder $imageMetadataBuilder;

    /**
     * @param ResponseFactory $responseFactory
     * @param DeerRadioDataAccessor $dataAccessor
     * @param ImageMetadataBuilder $imageMetadataBuilder
     */
    public function __construct(
        ResponseFactory $responseFactory,
        DeerRadioDataAccessor $dataAccessor,
        ImageMetadataBuilder $imageMetadataBuilder
    )
    {
        $this->responseFactory = $responseFactory;
        $this->dataAccessor = $dataAccessor;
        $this->imageMetadataBuilder = $imageMetadataBuilder;
    }

    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /** @var ImageData $imageData */
        $imageData = $this->dataAccessor->getValue(DeerRadioDataKey::CURRENT_IMAGE_DATA->value);
        if ($imageData === null) {
            throw new HttpException(500, 'No image data found');
        }

        return $this->responseFactory->json([
            'metadata' => $this->imageMetadataBuilder->buildFromImageData($imageData),
            'path' => $imageData->getPath(),
        ]);
    }
}
