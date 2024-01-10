<?php

declare(strict_types=1);

namespace App\Components\Output\Filler;

use App\Components\Output\Entity\Output;

class OutputFiller
{
    /**
     * Fills output object from input data
     * @param Output $output
     * @param array<string, mixed> $input
     * @return Output
     */
    public function fillFromArray(Output $output, array $input) : Output
    {
        $output->setOutputName($input['outputName']);
        $output->setDriverName($input['driverName']);
        $output->setDriverConfig($input['driverConfig']);
        $output->setIsActive((bool) $input['isActive']);

        return $output;
    }
}
