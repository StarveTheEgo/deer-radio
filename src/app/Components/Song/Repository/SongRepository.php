<?php

declare(strict_types=1);

namespace App\Components\Song\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Song\Criteria\DeerRadioSongCriteria;
use App\Components\Song\Entity\Song;

class SongRepository extends AbstractRepository implements SongRepositoryInterface
{
    public function create(Song $song): void
    {
        parent::createObject($song);
    }

    public function update(Song $song): void
    {
        parent::updateObject($song);
    }

    public function delete(Song $song): void
    {
        parent::deleteObject($song);
    }

    public function findIdsByCriteria(DeerRadioSongCriteria $criteria): array
    {
        $queryBuilder = $this
            ->createQueryBuilder('song')
            ->select('song.id')
            ->leftJoin('song.author', 'author')
            ->andWhere('song.isActive = 1')
            ->andWhere('author.isActive = 1')
            ->orderBy('song.finishedAt', 'ASC');

        $avoidableSongIds = $criteria->getAvoidableSongIds();
        if (count($avoidableSongIds) > 0) {
            $queryBuilder = $queryBuilder
                ->andWhere($queryBuilder->expr()->notIn('song.id', ':avoidableSongIds'))
                ->setParameter('avoidableSongIds', $avoidableSongIds);
        }

        $avoidableAuthorIds = $criteria->getAvoidableAuthorIds();
        if (count($avoidableAuthorIds) > 0) {
            $queryBuilder = $queryBuilder
                ->andWhere($queryBuilder->expr()->notIn('author.id', ':avoidableAuthorIds'))
                ->setParameter('avoidableAuthorIds', $avoidableAuthorIds);
        }

        $authorIds = $criteria->getSuitableAuthorIds();
        if (count($authorIds) > 0) {
            $queryBuilder = $queryBuilder
                ->andWhere($queryBuilder->expr()->in('author.id', ':suitableAuthorIds'))
                ->setParameter('suitableAuthorIds', $authorIds);
        }

        $maxSongFinishTime = $criteria->getMaxSongFinishTime();
        if ($maxSongFinishTime !== null) {
            $queryBuilder = $queryBuilder
                ->andWhere($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->lte('song.finishedAt', ':maxSongFinishTime'),
                    $queryBuilder->expr()->isNull('song.finishedAt')
                ))
                ->setParameter('maxSongFinishTime', $maxSongFinishTime);
        }

        $limit = $criteria->getLimit();
        if ($limit !== null) {
            $queryBuilder->setMaxResults($limit);
        }

        $result = $queryBuilder
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'id');
    }
}
