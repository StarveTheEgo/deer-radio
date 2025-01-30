<?php

declare(strict_types=1);

namespace App\Components\Storage\Adapter;

use App\Components\DeerRadio\Enum\DeerRadioRoute;
use Illuminate\Routing\UrlGenerator;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;
use League\Flysystem\WebDAV\WebDAVAdapter;

class WebDavAdapterWithFakeUrl implements FilesystemAdapter, PublicUrlGenerator
{
    private WebDAVAdapter $originalAdapter;

    private UrlGenerator $urlGenerator;

    public function __construct(WebDAVAdapter $originalAdapter, UrlGenerator $urlGenerator)
    {
        $this->originalAdapter = $originalAdapter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Generates no-op (ping) route, because WebDav adapter currently does not support public URL generation
     * @fixme
     *
     * @param string $path
     * @return string
     */
    public function getUrl(string $path) : string
    {
        return $this->urlGenerator->route(DeerRadioRoute::PING->value);
    }

    public function fileExists(string $path): bool
    {
        return $this->originalAdapter->fileExists($path);
    }

    public function directoryExists(string $path): bool
    {
        return $this->originalAdapter->directoryExists($path);
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $this->originalAdapter->write($path, $contents, $config);
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->originalAdapter->writeStream($path, $contents, $config);
    }

    public function read(string $path): string
    {
        return $this->originalAdapter->read($path);
    }

    public function readStream(string $path)
    {
        return $this->originalAdapter->readStream($path);
    }

    public function delete(string $path): void
    {
        $this->originalAdapter->delete($path);
    }

    public function deleteDirectory(string $path): void
    {
        $this->originalAdapter->deleteDirectory($path);
    }

    public function createDirectory(string $path, Config $config): void
    {
        $this->originalAdapter->createDirectory($path, $config);
    }

    public function setVisibility(string $path, string $visibility): void
    {
        $this->originalAdapter->setVisibility($path, $visibility);
    }

    public function visibility(string $path): FileAttributes
    {
        return $this->originalAdapter->visibility($path);
    }

    public function mimeType(string $path): FileAttributes
    {
        return $this->originalAdapter->mimeType($path);
    }

    public function lastModified(string $path): FileAttributes
    {
        return $this->originalAdapter->lastModified($path);
    }

    public function fileSize(string $path): FileAttributes
    {
        return $this->originalAdapter->fileSize($path);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        return $this->originalAdapter->listContents($path, $deep);
    }

    public function move(string $source, string $destination, Config $config): void
    {
        $this->originalAdapter->move($source, $destination, $config);
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        $this->originalAdapter->copy($source, $destination, $config);
    }

    public function publicUrl(string $path, Config $config): string
    {
        return $this->originalAdapter->publicUrl($path, $config);
    }
}
