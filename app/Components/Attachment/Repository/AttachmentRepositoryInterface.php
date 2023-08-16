<?php

declare(strict_types=1);

namespace App\Components\Attachment\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\Attachment\Entity\Attachment;

interface AttachmentRepositoryInterface extends RepositoryInterface
{
    public function create(Attachment $attachment);

    public function update(Attachment $attachment): void;

    public function delete(Attachment $attachment): void;
}
