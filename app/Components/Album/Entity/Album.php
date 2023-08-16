<?php

declare(strict_types=1);

namespace App\Components\Album\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use App\Components\Song\Entity\Song;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'albums')]
#[Orm\UniqueConstraint(name: 'albums_title_unique', columns: ['title'])]
class Album extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING)]
    protected string $title;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $year;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Song::class)]
    protected ArrayCollection $songs;

    public function __construct() {
        $this->songs = new ArrayCollection();
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Album
     */
    public function setTitle(string $title): Album
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return Album
     */
    public function setYear(int $year): Album
    {
        $this->year = $year;
        return $this;
    }
}
