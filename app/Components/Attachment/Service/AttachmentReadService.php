<?php

declare(strict_types=1);

namespace App\Components\Attachment\Service;

use App\Components\Attachment\Repository\AttachmentRepositoryInterface;

class AttachmentReadService
{
    private AttachmentRepositoryInterface $repository;

    public function __construct(AttachmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
