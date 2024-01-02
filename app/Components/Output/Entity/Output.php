<?php

declare(strict_types=1);

namespace App\Components\Output\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ORM\Contracts\UrlRoutable;

#[Orm\Entity]
#[Orm\Table(name: 'outputs')]
#[Orm\UniqueConstraint(name: 'unique_output', columns: ['output_name', 'driver_name'])]
class Output extends AbstractDomainObject implements TimestampableInterface, UrlRoutable
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING)]
    protected string $outputName;

    #[ORM\Column(type: Types::STRING)]
    protected string $driverName;

    #[ORM\Column(type: Types::JSON)]
    protected array $driverConfig;

    #[ORM\Column(type: Types::BOOLEAN)]
    protected bool $isActive;

    /**
     * @return string
     */
    public static function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Output
     */
    public function setId(?int $id): Output
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputName(): string
    {
        return $this->outputName;
    }

    /**
     * @param string $outputName
     * @return Output
     */
    public function setOutputName(string $outputName): Output
    {
        $this->outputName = $outputName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDriverName(): string
    {
        return $this->driverName;
    }

    /**
     * @param string $driverName
     * @return Output
     */
    public function setDriverName(string $driverName): Output
    {
        $this->driverName = $driverName;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDriverConfig(): array
    {
        return $this->driverConfig;
    }

    /**
     * @param array<string, mixed> $driverConfig
     * @return Output
     */
    public function setDriverConfig(array $driverConfig): Output
    {
        $this->driverConfig = $driverConfig;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }
}
