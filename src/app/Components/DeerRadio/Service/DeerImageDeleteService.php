<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\DeerRadio\Enum\DeerRadioPath;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class DeerImageDeleteService
{
    private Filesystem $radioStorage;

    public function __construct(Filesystem $radioStorage)
    {
        $this->radioStorage = $radioStorage;
    }

    public function removeOldImages(): void
    {
        $deerImagesDirectoryName = DeerRadioPath::DEER_IMAGES_DIR->value;
        $deerImageDirectoryPath = $this->radioStorage->path($deerImagesDirectoryName);

        $finder = new Finder();
        $finder
            ->files()
            ->in($deerImageDirectoryPath)
            ->depth('== 0')
            ->name(DeerImageUpdateService::DEER_IMAGE_PREFIX.'*.jpg')
            ->date('< 5 minute ago')
            ->sortByAccessedTime();

        foreach ($finder as $file) {
            $this->radioStorage->delete($deerImagesDirectoryName.DIRECTORY_SEPARATOR.$file->getFilename());
        }
    }
}
