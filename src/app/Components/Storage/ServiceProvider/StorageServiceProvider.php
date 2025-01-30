<?php

declare(strict_types=1);

namespace App\Components\Storage\ServiceProvider;

use App\Components\Storage\Adapter\WebDavAdapterWithFakeUrl;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client as DavClient;

class StorageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /** @var FilesystemManager $fsManager */
        $fsManager = $this->app->get(FilesystemManager::class);

        $fsManager->extend('webdav', function (Application $app, array $config) {
            $client = new DavClient([
                'baseUri' => $config['baseUri'],
                'userName' => $config['user'],
                'password' => $config['password'],
            ]);

            $originalAdapter = new WebDAVAdapter($client);
            $adapter = new WebDavAdapterWithFakeUrl($originalAdapter, $app->make(UrlGenerator::class));

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
    }
}
