<?php

declare(strict_types=1);

namespace App\Components\AuthorLink\Entity;

use App\Components\Author\Entity\Author;
use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'author_links')]
#[Orm\UniqueConstraint(name: 'authorLinks_title_unique', columns: ['author_id', 'url'])]
class AuthorLink extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'links')]
    protected Author $author;

    #[ORM\Column(type: Types::STRING)]
    protected string $url;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return AuthorLink
     */
    public function setAuthor(Author $author): AuthorLink
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return AuthorLink
     */
    public function setUrl(string $url): AuthorLink
    {
        $this->url = $url;
        return $this;
    }
}
