<?php

declare(strict_types=1);

namespace App\Components\Attachment\Service;

use App\Components\Attachment\Repository\AttachmentRepositoryInterface;

class AttachmentReadService
{
    public function __construct(private readonly AttachmentRepositoryInterface $repository)
    {
    }
}
