<?php

declare(strict_types=1);

namespace App\Components\Output\Interfaces;

use App\Components\Output\Entity\Output;
use App\Components\Output\Enum\OutputStreamState;

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
     * Prepares livestream for specified output
     * @param Output $output
     * @return void
     */
    public function prepareLiveStream(Output $output) : void;

    /**
     * Configuration of output for Liquidsoap
     * @return array<string, mixed>
     */
    public function getLiquidsoapPayload(Output $output) : array;

    /**
     * @param Output $output
     * @return OutputStreamState
     */
    public function getStreamState(Output $output) : OutputStreamState;
}
