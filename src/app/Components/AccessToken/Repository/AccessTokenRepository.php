<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Repository;

use App\Components\AccessToken\Helper\AccessTokenExpirationDateHelper;
use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\AccessToken\Entity\AccessToken;
use DateTimeImmutable;

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

    /**
     * @return iterable<AccessToken>
     */
    public function iterateExpiredRefreshableAccessTokens(): iterable
    {
        $qb = $this->createQueryBuilder('accessToken');

        $query = $this->createQueryBuilder('accessToken')
            ->andWhere('accessToken.expiresAt <= :expirationStart')
            ->andWhere($qb->expr()->isNotNull('accessToken.refreshToken'))
            ->getQuery();

        $currentDateTime = new DateTimeImmutable();
        $expirationStart = ($currentDateTime->modify(sprintf(
            '-%d seconds',
            AccessTokenExpirationDateHelper::REFRESH_TIME_WINDOW_START
        )));

        return $query->toIterable([
            'expirationStart' => $expirationStart,
        ]);
    }
}
