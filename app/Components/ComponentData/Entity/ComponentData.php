<?php

declare(strict_types=1);

namespace App\Components\ComponentData\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'component_data')]
#[Orm\UniqueConstraint(name: '_idx_unique_field', columns: ['component', 'field'])]
class ComponentData extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING)]
    protected string $component;

    #[ORM\Column(type: Types::STRING)]
    protected string $field;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComponent(): string
    {
        return $this->component;
    }

    public function setComponent(string $component): ComponentData
    {
        $this->component = $component;

        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): ComponentData
    {
        $this->field = $field;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): ComponentData
    {
        $this->value = $value;

        return $this;
    }
}
