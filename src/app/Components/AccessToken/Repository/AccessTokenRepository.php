<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\AccessToken\Entity\AccessToken;

class AccessTokenRepository extends AbstractRepository implements AccessTokenRepositoryInterface
{
    public function create(AccessToken $AccessToken): void
    {
        parent::createObject($AccessToken);
    }

    public function update(AccessToken $AccessToken): void
    {
        parent::updateObject($AccessToken);
    }

    public function delete(AccessToken $AccessToken): void
    {
        parent::deleteObject($AccessToken);
    }
}
