<?php

declare(strict_types=1);

namespace App\Components\Google;

use App\Components\Storage\Enum\StorageName;
use App\Components\Google\Factory\GoogleClientFactory;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public const COMPONENT_NAME = 'google';

    public $singletons = [
        GoogleClientFactory::class => GoogleClientFactory::class,
    ];

    public function provides(): array
    {
        return [
            GoogleClientFactory::class,
        ];
    }

}
