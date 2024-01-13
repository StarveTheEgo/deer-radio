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
        $deerImageDirectory = $this->radioStorage->path(DeerRadioPath::DEER_IMAGES_DIR->value);

        $finder = new Finder();
        $finder
            ->files()
            ->in($deerImageDirectory)
            ->depth('== 0')
            ->name(DeerImageUpdateService::DEER_IMAGE_PREFIX.'*.jpg')
            ->date('< 5 minute ago')
            ->sortByAccessedTime();

        foreach ($finder as $file) {
            $this->radioStorage->delete($file->getPath());
        }
    }
}
