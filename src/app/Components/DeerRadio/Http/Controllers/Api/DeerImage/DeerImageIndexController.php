<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Http\Controllers\Api\DeerImage;

use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\DeerRadio\Metadata\ImageMetadataBuilder;
use App\Components\ImageData\ImageData;
use App\Components\Liquidsoap\AnnotationBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use JsonException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeerImageIndexController extends Controller
{
    /**
     * @param ResponseFactory $responseFactory
     * @param DeerRadioDataAccessor $dataAccessor
     * @param ImageMetadataBuilder $imageMetadataBuilder
     * @param AnnotationBuilder $annotationBuilder
     */
    public function __construct(private readonly ResponseFactory $responseFactory, private readonly DeerRadioDataAccessor $dataAccessor, private readonly ImageMetadataBuilder $imageMetadataBuilder, private readonly AnnotationBuilder $annotationBuilder)
    {
    }

    /**
     * @return JsonResponse
     * @throws JsonException
     */
    public function index() : JsonResponse
    {
        /** @var ImageData $imageData */
        $imageData = $this->dataAccessor->getValue(DeerRadioDataKey::CURRENT_IMAGE_DATA->value);
        if ($imageData === null) {
            // @todo return random local image from config instead
            // @todo return fallback image (configured in .env or somewhere)
            throw new HttpException(500, 'No image data found');
        }

        $imagePath = $imageData->getPath();
        $metadata = $this->imageMetadataBuilder->buildFromImageData($imageData);

        return $this->responseFactory->json([
            'path' => $imagePath,
            'metadata' => $metadata,
            'annotatedPath' => $this->annotationBuilder->buildDataAnnotation($imagePath, $metadata)
        ]);
    }
}
