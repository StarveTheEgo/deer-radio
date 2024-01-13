<?php

declare(strict_types=1);

namespace App\Components\Attachment\Helper;

use App\Components\Attachment\Entity\Attachment;
use App\Components\Liquidsoap\AnnotationBuilder;
use Illuminate\Filesystem\FilesystemManager;
use LogicException;

class AttachmentPathHelper
{
    private FilesystemManager $filesystemManager;

    /**
     * @param FilesystemManager $filesystemManager
     * @param AnnotationBuilder $annotationBuilder
     * @param AttachmentPathHelper $attachmentPathHelper
     */
    public function __construct(
        FilesystemManager $filesystemManager,
        AnnotationBuilder $annotationBuilder,
        AttachmentPathHelper $attachmentPathHelper
    )
    {
        $this->filesystemManager = $filesystemManager;
        $this->annotationBuilder = $annotationBuilder;
        $this->attachmentPathHelper = $attachmentPathHelper;
    }

    /**
     * @param Attachment $attachment
     * @return string
     */
    public function buildPathOnDisk(Attachment $attachment): string
    {
        return $attachment->getPath().$attachment->getName().'.'.$attachment->getExtension();
    }

    /**
     * @param Attachment $attachment
     * @return string
     */
    public function getExistingPathOnDisk(Attachment $attachment) : string
    {
        $path = $this->buildPathOnDisk($attachment);

        $disk = $this->filesystemManager->disk($attachment->getDisk());
        if (!$disk->exists($path)) {
            throw new LogicException(sprintf(
                'Attachment #%d does not exist in: %s',
                $attachment->getId(),
                $path
            ));
        }

        return $path;
    }
}
