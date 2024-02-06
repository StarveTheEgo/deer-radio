<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Repository;

use App\Components\AccessToken\Helper\AccessTokenExpirationDateHelper;
use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\AccessToken\Entity\AccessToken;
use DateTimeImmutable;
use Doctrine\ORM\Query;

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
        // pick the date when we will consider tokens invalid
        $expirationStart = (new DateTimeImmutable())
            ->modify(sprintf(
                '+%d seconds',
                AccessTokenExpirationDateHelper::REFRESH_TIME_WINDOW_START
            ));

        $em = $this->getEntityManager();
        $query = $this->buildExpiredRefreshableAccessTokensQuery($expirationStart);
        /** @var AccessToken $accessToken */
        foreach ($query->toIterable() as $accessToken) {
            yield $accessToken;

            $em->detach($accessToken);
        }
    }

    /**
     * @param DateTimeImmutable $expirationStartDateTime
     * @return Query
     */
    private function buildExpiredRefreshableAccessTokensQuery(DateTimeImmutable $expirationStartDateTime): Query
    {
        $qb = $this->createQueryBuilder('accessToken');

        return $qb
            ->andWhere('accessToken.expiresAt <= :expirationStart')
            ->setParameter('expirationStart', $expirationStartDateTime)
            ->andWhere($qb->expr()->isNotNull('accessToken.refreshToken'))
            ->getQuery();
    }
}
