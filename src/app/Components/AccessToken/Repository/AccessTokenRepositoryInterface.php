<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\AccessToken\Entity\AccessToken;

interface AccessTokenRepositoryInterface extends RepositoryInterface
{
    public function create(AccessToken $AccessToken);

    public function update(AccessToken $AccessToken): void;

    public function delete(AccessToken $AccessToken): void;

    /**
     * @return iterable<AccessToken>
     */
    public function iterateExpiredRefreshableAccessTokens() : iterable;
}
