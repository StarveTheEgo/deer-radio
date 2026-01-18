<?php

declare(strict_types=1);

namespace App\Components\Attachment\Service;

use App\Components\Attachment\Entity\Attachment;
use App\Components\Attachment\Repository\AttachmentRepositoryInterface;

class AttachmentCreateService
{
    public function __construct(private readonly AttachmentRepositoryInterface $repository)
    {
    }

    public function create(Attachment $attachment): void
    {
        $this->repository->create($attachment);
    }
}
