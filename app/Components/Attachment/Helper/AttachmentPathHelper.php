<?php

declare(strict_types=1);

namespace App\Components\Attachment\Helper;

use App\Components\Attachment\Entity\Attachment;

class AttachmentPathHelper
{
    /**
     * @param Attachment $attachment
     * @return string
     */
    public function getPathOnDisk(Attachment $attachment): string
    {
        return $attachment->getPath().$attachment->getName().'.'.$attachment->getExtension();
    }
}
