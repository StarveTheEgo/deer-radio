<?php

declare(strict_types=1);

namespace App\Components\Author\Entity;

use App\Components\AuthorLink\Entity\AuthorLink;
use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use App\Components\Song\Entity\Song;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'authors')]
#[Orm\UniqueConstraint(name: 'authors_name_unique', columns: ['name'])]
class Author extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING)]
    protected string $name;

    #[ORM\Column(type: Types::BOOLEAN)]
    protected bool $isActive = true;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $playedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $finishedAt;

    #[ORM\Column(type: Types::BIGINT)]
    protected int $playedCount = 0;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $unsplashSearchQuery;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Song::class)]
    protected Collection $songs;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Author::class)]
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
     * @return Author
     */
    public function setName(string $name): Author
    {
        $this->name = $name;
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
     * @return Author
     */
    public function setIsActive(bool $isActive): Author
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getPlayedAt(): ?DateTimeImmutable
    {
        return $this->playedAt;
    }

    /**
     * @param DateTimeImmutable|null $playedAt
     * @return Author
     */
    public function setPlayedAt(?DateTimeImmutable $playedAt): Author
    {
        $this->playedAt = $playedAt;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getFinishedAt(): ?DateTimeImmutable
    {
        return $this->finishedAt;
    }

    /**
     * @param DateTimeImmutable|null $finishedAt
     * @return Author
     */
    public function setFinishedAt(?DateTimeImmutable $finishedAt): Author
    {
        $this->finishedAt = $finishedAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlayedCount(): int
    {
        return $this->playedCount;
    }

    /**
     * @param int $playedCount
     * @return Author
     */
    public function setPlayedCount(int $playedCount): Author
    {
        $this->playedCount = $playedCount;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnsplashSearchQuery(): ?string
    {
        return $this->unsplashSearchQuery;
    }

    /**
     * @param string|null $unsplashSearchQuery
     * @return Author
     */
    public function setUnsplashSearchQuery(?string $unsplashSearchQuery): Author
    {
        $this->unsplashSearchQuery = $unsplashSearchQuery;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSongs(): ArrayCollection
    {
        return $this->songs;
    }

    /**
     * @return AuthorLink[]
     */
    public function getLinks(): array
    {
        return $this->links->toArray();
    }
}
