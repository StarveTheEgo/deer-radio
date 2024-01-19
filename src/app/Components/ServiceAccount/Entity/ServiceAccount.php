<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use App\Components\AccessToken\Entity\AccessToken;
use App\Components\User\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ORM\Contracts\UrlRoutable;

#[Orm\Entity]
#[Orm\Table(name: 'service_accounts')]
#[Orm\UniqueConstraint(name: 'unique_account', columns: ['user_id', 'account_name'])]
class ServiceAccount extends AbstractDomainObject implements TimestampableInterface, UrlRoutable
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    protected User $user;

    #[ORM\Column(type: Types::STRING)]
    protected string $accountName;

    #[ORM\Column(type: Types::STRING)]
    protected string $serviceName;

    #[ORM\OneToOne(targetEntity: AccessToken::class)]
    #[ORM\JoinColumn(name: 'access_token_id', referencedColumnName: 'id')]
    protected ?AccessToken $accessToken = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    protected bool $isActive = true;

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
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return ServiceAccount
     */
    public function setUser(User $user): ServiceAccount
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountName(): string
    {
        return $this->accountName;
    }

    /**
     * @param string $accountName
     * @return ServiceAccount
     */
    public function setAccountName(string $accountName): ServiceAccount
    {
        $this->accountName = $accountName;
        return $this;
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
     * @return ServiceAccount
     */
    public function setServiceName(string $serviceName): ServiceAccount
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * @return AccessToken|null
     */
    public function getAccessToken(): ?AccessToken
    {
        return $this->accessToken;
    }

    /**
     * @param AccessToken|null $accessToken
     * @return ServiceAccount
     */
    public function setAccessToken(?AccessToken $accessToken): ServiceAccount
    {
        $this->accessToken = $accessToken;
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
     * @return ServiceAccount
     */
    public function setIsActive(bool $isActive): ServiceAccount
    {
        $this->isActive = $isActive;
        return $this;
    }
}
