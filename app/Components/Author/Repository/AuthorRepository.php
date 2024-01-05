<?php

declare(strict_types=1);

namespace App\Components\Author\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Author\Entity\Author;
use DateTimeImmutable;

class AuthorRepository extends AbstractRepository implements AuthorRepositoryInterface
{
    public function create(Author $author): void
    {
        parent::createObject($author);
    }

    public function update(Author $author): void
    {
        parent::updateObject($author);
    }

    public function delete(Author $author): void
    {
        parent::deleteObject($author);
    }

    /**
     * @param DateTimeImmutable $maxFinishedAt
     * @return int[]
     */
    public function getLeastPlayedAuthorIds(DateTimeImmutable $maxFinishedAt) : array
    {
        $queryBuilder = $this->createQueryBuilder('author');
        $result = $queryBuilder
            ->select('author.id')
            ->add('where', $queryBuilder->expr()->orX(
                $queryBuilder->expr()->lte('author.finishedAt', ':authorFinishedBefore'),
                $queryBuilder->expr()->isNull('author.finishedAt')
            ))
            ->setParameters([
                'authorFinishedBefore' => $maxFinishedAt,
            ])
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'id');
    }
}
