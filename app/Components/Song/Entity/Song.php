<?php

declare(strict_types=1);

namespace App\Components\Song\Entity;

use App\Components\Album\Entity\Album;
use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use App\Components\Label\Entity\Label;
use App\Models\Author;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'songs')]
#[Orm\UniqueConstraint(name: 'songs_author_id_album_id_title_unique', columns: ['authorId', 'albumId', 'title'])]
class Song extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING)]
    protected string $title;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'song')]
    protected Author $author;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'song')]
    protected ?Album $album;

    #[ORM\ManyToOne(targetEntity: Label::class, inversedBy: 'song')]
    protected ?Label $label;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $year;

    #[ORM\Column(type: Types::TEXT)]
    protected string $source;

    /**
     * @var int
     * @deprecated Is not being used (yet?)
     */
    #[ORM\Column(type: Types::SMALLINT)]
    protected int $tempo = 0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $playedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $finishedAt;

    #[ORM\Column(type: Types::BIGINT)]
    protected int $playedCount = 0;

    #[ORM\Column(type: Types::BOOLEAN)]
    protected bool $isActive = true;

    #[ORM\Column(type: Types::SMALLINT)]
    protected int $volume = 100;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $unsplashSearchQuery;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $songAttachmentData = null;

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
     * @return Song
     */
    public function setTitle(string $title): Song
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Author
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }

    /**
     * @param Author $author
     * @return Song
     */
    public function setAuthor(Author $author): Song
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return Album|null
     */
    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    /**
     * @param Album|null $album
     * @return Song
     */
    public function setAlbum(?Album $album): Song
    {
        $this->album = $album;
        return $this;
    }

    /**
     * @return Label|null
     */
    public function getLabel(): ?Label
    {
        return $this->label;
    }

    /**
     * @param Label|null $label
     * @return Song
     */
    public function setLabel(?Label $label): Song
    {
        $this->label = $label;
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
     * @return Song
     */
    public function setYear(int $year): Song
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return Song
     */
    public function setSource(string $source): Song
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return int
     */
    public function getTempo(): int
    {
        return $this->tempo;
    }

    /**
     * @param int $tempo
     * @return Song
     */
    public function setTempo(int $tempo): Song
    {
        $this->tempo = $tempo;
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
     * @return Song
     */
    public function setPlayedAt(?DateTimeImmutable $playedAt): Song
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
     * @return Song
     */
    public function setFinishedAt(?DateTimeImmutable $finishedAt): Song
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
     * @return Song
     */
    public function setPlayedCount(int $playedCount): Song
    {
        $this->playedCount = $playedCount;
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
     * @return Song
     */
    public function setIsActive(bool $isActive): Song
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return int
     */
    public function getVolume(): int
    {
        return $this->volume;
    }

    /**
     * @param int $volume
     * @return Song
     */
    public function setVolume(int $volume): Song
    {
        $this->volume = $volume;
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
     * @return Song
     */
    public function setUnsplashSearchQuery(?string $unsplashSearchQuery): Song
    {
        $this->unsplashSearchQuery = $unsplashSearchQuery;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSongAttachmentData(): ?string
    {
        return $this->songAttachmentData;
    }

    /**
     * @param string|null $songAttachmentData
     * @return Song
     */
    public function setSongAttachmentData(?string $songAttachmentData): Song
    {
        $this->songAttachmentData = $songAttachmentData;
        return $this;
    }
}
