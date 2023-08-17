<?php

declare(strict_types=1);

namespace App\Components\User\Entity;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\TimestampableEntityTrait;
use App\Components\DoctrineOrchid\TimestampableInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Orm\Entity]
#[Orm\Table(name: 'users')]
#[Orm\UniqueConstraint(name: 'users_email_unique', columns: ['email'])]
class User extends AbstractDomainObject implements TimestampableInterface
{
    use TimestampableEntityTrait;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING)]
    protected string $name;

    #[ORM\Column(type: Types::STRING)]
    protected string $email;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $emailVerifiedAt;

    #[ORM\Column(type: Types::STRING)]
    protected string $password;

    #[ORM\Column(type: Types::STRING)]
    protected string $rememberToken;

    #[ORM\Column(type: Types::STRING)]
    protected string $permissions;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return User
     */
    public function setId(?int $id): User
    {
        $this->id = $id;
        return $this;
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
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getEmailVerifiedAt(): ?DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    /**
     * @param DateTimeImmutable|null $emailVerifiedAt
     * @return User
     */
    public function setEmailVerifiedAt(?DateTimeImmutable $emailVerifiedAt): User
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    /**
     * @param string $rememberToken
     * @return User
     */
    public function setRememberToken(string $rememberToken): User
    {
        $this->rememberToken = $rememberToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getPermissions(): string
    {
        return $this->permissions;
    }

    /**
     * @param string $permissions
     * @return User
     */
    public function setPermissions(string $permissions): User
    {
        $this->permissions = $permissions;
        return $this;
    }
}
