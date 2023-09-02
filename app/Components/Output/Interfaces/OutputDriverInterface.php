<?php

declare(strict_types=1);

namespace App\Components\Output\Interfaces;

interface OutputDriverInterface
{
    /**
     * Returns technical name of output driver
     * @return string
     */
    public static function getTechnicalName() : string;

    /**
     * Returns title of output driver
     * @return string
     */
    public static function getTitle() : string;

    /**
     * Configuration of output for Liquidsoap
     * @return array
     */
    public function getLiquidsoapPayload() : array;
}
