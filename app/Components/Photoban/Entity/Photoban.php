<?php

declare(strict_types=1);

namespace App\Components\Photoban\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'photobans')]
#[Orm\UniqueConstraint(name: '_idx_unique_key', columns: ['key'])]
#[Orm\UniqueConstraint(name: '_idx_image_url', columns: ['imageUrl'])]
class Photoban extends AbstractDomainObject
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING)]
    protected string $imageUrl;

    #[ORM\Column(type: Types::STRING)]
    protected string $reason;

    public function getId()
    {
        return $this->getId();
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return Photoban
     */
    public function setImageUrl(string $imageUrl): Photoban
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     * @return Photoban
     */
    public function setReason(string $reason): Photoban
    {
        $this->reason = $reason;
        return $this;
    }
}
