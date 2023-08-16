<?php

declare(strict_types=1);

namespace App\Components\Attachment\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'attachments')]
class Attachment extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(type: Types::TEXT)]
    protected string $name;

    #[ORM\Column(type: Types::TEXT)]
    protected string $originalName;

    #[ORM\Column(type: Types::STRING)]
    protected ?string $extension;

    #[ORM\Column(type: Types::BIGINT)]
    protected string $size;

    #[ORM\Column(type: Types::INTEGER)]
    protected string $sort;

    #[ORM\Column(type: Types::STRING)]
    protected string $path;

    #[ORM\Column(type: Types::TEXT)]
    protected string $description;

    #[ORM\Column(type: Types::TEXT)]
    protected ?string $alt = null;

    #[ORM\Column(type: Types::TEXT)]
    protected ?string $hash = null;

    #[ORM\Column(type: Types::STRING)]
    protected string $disk = 'public';

    #[ORM\Column(type: Types::BIGINT)]
    protected ?string $userId = null;

    #[ORM\Column(type: Types::STRING)]
    protected ?string $group = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Attachment
     */
    public function setName(string $name): Attachment
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @param string $originalName
     * @return Attachment
     */
    public function setOriginalName(string $originalName): Attachment
    {
        $this->originalName = $originalName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string|null $extension
     * @return Attachment
     */
    public function setExtension(?string $extension): Attachment
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @param string $size
     * @return Attachment
     */
    public function setSize(string $size): Attachment
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     * @return Attachment
     */
    public function setSort(string $sort): Attachment
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Attachment
     */
    public function setPath(string $path): Attachment
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Attachment
     */
    public function setDescription(string $description): Attachment
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAlt(): ?string
    {
        return $this->alt;
    }

    /**
     * @param string|null $alt
     * @return Attachment
     */
    public function setAlt(?string $alt): Attachment
    {
        $this->alt = $alt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @param string|null $hash
     * @return Attachment
     */
    public function setHash(?string $hash): Attachment
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    /**
     * @param string $disk
     * @return Attachment
     */
    public function setDisk(string $disk): Attachment
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param string|null $userId
     * @return Attachment
     */
    public function setUserId(?string $userId): Attachment
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * @param string|null $group
     * @return Attachment
     */
    public function setGroup(?string $group): Attachment
    {
        $this->group = $group;
        return $this;
    }
}
