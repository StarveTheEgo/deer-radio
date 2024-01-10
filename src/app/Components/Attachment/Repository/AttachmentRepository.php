<?php

declare(strict_types=1);

namespace App\Components\Attachment\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Attachment\Entity\Attachment;

class AttachmentRepository extends AbstractRepository implements AttachmentRepositoryInterface
{
    public function create(Attachment $attachment): void
    {
        parent::createObject($attachment);
    }

    public function update(Attachment $attachment): void
    {
        parent::updateObject($attachment);
    }

    public function delete(Attachment $attachment): void
    {
        parent::deleteObject($attachment);
    }
}
