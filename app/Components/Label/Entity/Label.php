<?php

declare(strict_types=1);

namespace App\Components\Label\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use App\Components\LabelLink\Entity\LabelLink;
use App\Components\Song\Entity\Song;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'labels')]
#[Orm\UniqueConstraint(name: 'labels_title_unique', columns: ['name'])]
class Label extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING)]
    protected string $name;

    #[ORM\OneToMany(mappedBy: 'label', targetEntity: Song::class)]
    protected Collection $songs;

    #[ORM\OneToMany(mappedBy: 'label', targetEntity: LabelLink::class)]
    protected Collection $links;

    public function __construct() {
        $this->songs = new ArrayCollection();
        $this->links = new ArrayCollection();
    }

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
     * @return Label
     */
    public function setName(string $name): Label
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return LabelLink[]
     */
    public function getLinks(): array
    {
        return $this->links->toArray();
    }
}
