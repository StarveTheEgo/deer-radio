<?php

declare(strict_types=1);

namespace App\Components\LabelLink\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use App\Components\Label\Entity\Label;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'label_links')]
#[Orm\UniqueConstraint(name: 'label_links_label_id_url_unique', columns: ['label_id', 'url'])]
class LabelLink extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\ManyToOne(targetEntity: Label::class, inversedBy: 'links')]
    protected Label $label;

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
     * @return Label
     */
    public function getLabel(): Label
    {
        return $this->label;
    }

    /**
     * @param Label $label
     * @return LabelLink
     */
    public function setLabel(Label $label): LabelLink
    {
        $this->label = $label;
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
     * @return LabelLink
     */
    public function setUrl(string $url): LabelLink
    {
        $this->url = $url;
        return $this;
    }
}
