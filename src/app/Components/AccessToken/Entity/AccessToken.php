<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'access_tokens')]
#[Orm\UniqueConstraint(name: 'unique_token', columns: ['service_name', 'oauth_identifier'])]
class AccessToken extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    protected string $serviceName;

    #[ORM\Column(type: Types::STRING)]
    protected string $oauthIdentifier;

    #[ORM\Column(type: Types::JSON)]
    protected ?array $scopes;

    #[ORM\Column(type: Types::STRING)]
    protected string $tokenType;

    #[ORM\Column(type: Types::TEXT)]
    protected string $authToken;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $refreshToken;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $expiresAt;

    /**
     * @return int|null
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     * @return AccessToken
     */
    public function setServiceName(string $serviceName): AccessToken
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * @return string
     */
    public function getOauthIdentifier(): string
    {
        return $this->oauthIdentifier;
    }

    /**
     * @param string $oauthIdentifier
     * @return AccessToken
     */
    public function setOauthIdentifier(string $oauthIdentifier): AccessToken
    {
        $this->oauthIdentifier = $oauthIdentifier;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getScopes(): ?array
    {
        return $this->scopes;
    }

    /**
     * @param array<string>|null $scopes
     * @return AccessToken
     */
    public function setScopes(?array $scopes): AccessToken
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @param string $tokenType
     * @return AccessToken
     */
    public function setTokenType(string $tokenType): AccessToken
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     * @return AccessToken
     */
    public function setAuthToken(string $authToken): AccessToken
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @param string|null $refreshToken
     * @return AccessToken
     */
    public function setRefreshToken(?string $refreshToken): AccessToken
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getExpiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    /**
     * @param DateTimeImmutable|null $expiresAt
     * @return AccessToken
     */
    public function setExpiresAt(?DateTimeImmutable $expiresAt): AccessToken
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }
}
