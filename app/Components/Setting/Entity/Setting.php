<?php

declare(strict_types=1);

namespace App\Components\Setting\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'settings')]
#[Orm\UniqueConstraint(name: '_idx_unique_key', columns: ['key'])]
class Setting extends AbstractDomainObject
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(name: '`key`', type: Types::STRING)]
    protected string $key;

    #[ORM\Column(type: Types::STRING)]
    protected string $fieldType;

    #[ORM\Column(type: Types::JSON)]
    protected ?array $fieldOptions;

    #[ORM\Column(type: Types::STRING)]
    protected string $description;

    #[ORM\Column(type: Types::STRING)]
    protected string $value;

    #[ORM\Column(type: Types::BOOLEAN)]
    protected bool $isEncrypted = false;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $ord;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): Setting
    {
        $this->key = $key;

        return $this;
    }

    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    public function setFieldType(string $fieldType): Setting
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    public function getFieldOptions(): ?array
    {
        return $this->fieldOptions;
    }

    public function setFieldOptions(?array $fieldOptions): Setting
    {
        $this->fieldOptions = $fieldOptions;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Setting
    {
        $this->description = $description;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): Setting
    {
        $this->value = $value;

        return $this;
    }

    public function isEncrypted(): bool
    {
        return $this->isEncrypted;
    }

    public function setIsEncrypted(bool $isEncrypted): Setting
    {
        $this->isEncrypted = $isEncrypted;

        return $this;
    }

    public function getOrd(): int
    {
        return $this->ord;
    }

    public function setOrd(int $ord): Setting
    {
        $this->ord = $ord;

        return $this;
    }
}
