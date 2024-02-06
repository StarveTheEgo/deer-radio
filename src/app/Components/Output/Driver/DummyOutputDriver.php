<?php

declare(strict_types=1);

namespace App\Components\Output\Driver;

use App\Components\Output\Entity\Output;
use App\Components\Output\Interfaces\OutputDriverInterface;

class DummyOutputDriver implements OutputDriverInterface
{
    public static function getTechnicalName(): string
    {
        return 'dummy';
    }

    public static function getTitle(): string
    {
        return 'Dummy output';
    }

    /**
     * @inheritDoc
     */
    public function prepareLiveStream(Output $output): void
    {
        // do nothing
    }

    /**
     * @inheritDoc
     */
    public function getLiquidsoapPayload(Output $output): array
    {
        return [];
    }
}
