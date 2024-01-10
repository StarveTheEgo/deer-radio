<?php

declare(strict_types=1);

namespace App\Components\ImageData;

class ImageData
{
    /** @var string Path to the image */
    private string $path;

    /** @var bool Is image located on a remote server */
    private bool $isRemote;

    /** @var string|null Url for the image */
    private ?string $imageUrl = null;

    /** @var string|null Url for the photoban check */
    private ?string $photobanUrl = null;

    /** @var string|null Image description */
    private ?string $description = null;

    /** @var string|null Author's name */
    private ?string $authorName = null;

    /** @var string|null Url for the author's profile */
    private ?string $profileUrl = null;

    public function __construct(string $path, bool $isRemote)
    {
        $this->setPath($path);

        $this->isRemote = $isRemote;
    }

    public function setPath(string $path): ImageData
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function getIsRemote(): bool
    {
        return $this->isRemote;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): ImageData
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhotobanUrl(): ?string
    {
        return $this->photobanUrl;
    }

    /**
     * @param string|null $photobanUrl
     * @return ImageData
     */
    public function setPhotobanUrl(?string $photobanUrl): ImageData
    {
        $this->photobanUrl = $photobanUrl;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): ImageData
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(?string $authorName): ImageData
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getProfileUrl(): ?string
    {
        return $this->profileUrl;
    }

    public function setProfileUrl(?string $profileUrl): ImageData
    {
        $this->profileUrl = $profileUrl;

        return $this;
    }

    public function toArray() : array {
        return [
            'path' => $this->getPath(),
            'isRemote' => $this->getIsRemote(),
            'imageUrl' => $this->getImageUrl(),
            'photobanUrl' => $this->getPhotobanUrl(),
            'profileUrl' => $this->getProfileUrl(),
            'authorName' => $this->getAuthorName(),
            'description' => $this->getDescription(),
        ];
    }
}
