<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Commands;

use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\ImageData\ImageData;
use App\Components\Liquidsoap\AnnotationBuilder;
use Illuminate\Console\Command;
use JsonException;
use RuntimeException;

class GetCurrentDeerImage extends Command
{
    public const IMAGE_DURATION = 30;

    public const IMAGE_DESCRIPTION_LIMIT = 60;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deer-image:current';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets current deer image data';

    private DeerRadioDataAccessor $dataAccessor;
    private AnnotationBuilder $liquidsoapAnnotationBuilder;

    public function __construct(
        DeerRadioDataAccessor $dataAccessor,
        AnnotationBuilder     $liquidsoapAnnotationBuilder,
    )
    {
        parent::__construct();

        $this->dataAccessor = $dataAccessor;
        $this->liquidsoapAnnotationBuilder = $liquidsoapAnnotationBuilder;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws JsonException
     */
    public function handle(): void
    {
        /** @var ImageData $imageData */
        $imageData = $this->dataAccessor->getValue(DeerRadioDataKey::CURRENT_IMAGE_DATA->value);
        if ($imageData === null) {
            throw new RuntimeException('No image data found');
        }

        $description = $imageData->getDescription();
        $description_length = mb_strlen($description);
        if ($description_length >= self::IMAGE_DESCRIPTION_LIMIT) {
            $imageData->setDescription(mb_substr($description, 0, self::IMAGE_DESCRIPTION_LIMIT).'…');
        }

        $imagePath = $imageData->getPath();
        if (!$imageData->getIsRemote() && !str_starts_with($imagePath, 'file://')) {
            // @todo proper protocol management
            $imageData->setPath('file://'.$imageData->getPath());
        }

        $imageDataArray = $imageData->toArray();
        $imageDataArray['duration'] = self::IMAGE_DURATION;

        $annotation = $this->liquidsoapAnnotationBuilder->buildDataAnnotation($imageData->getPath(), $imageDataArray);
        $this->line($annotation);
    }
}
