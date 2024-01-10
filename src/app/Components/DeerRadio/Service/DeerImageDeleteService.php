<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class DeerImageDeleteService
{
    private Filesystem $deerImageStorage;

    public function __construct(
        Filesystem $deerImageStorage
    )
    {
        $this->deerImageStorage = $deerImageStorage;
    }

    public function removeOldImages(): void
    {
        $deerImageDirectory = $this->deerImageStorage->path('');
        $finder = new Finder();
        $finder
            ->files()
            ->in($deerImageDirectory)
            ->depth('== 0')
            ->name(DeerImageUpdateService::DEER_IMAGE_PREFIX.'*.jpg')
            ->date('< 5 minute ago')
            ->sortByAccessedTime();

        foreach ($finder as $file) {
            @unlink($file->getRealPath());
        }
    }
}
