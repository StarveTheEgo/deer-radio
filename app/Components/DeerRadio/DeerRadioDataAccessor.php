<?php

declare(strict_types=1);

namespace App\Components\DeerRadio;

use App\Components\ComponentData\ComponentDataAccessor;
use App\Components\ComponentData\Service\ComponentDataAccessService;

class DeerRadioDataAccessor extends ComponentDataAccessor
{
    public function __construct(ComponentDataAccessService $service)
    {
        parent::__construct($service, DeerRadioServiceProvider::COMPONENT_NAME);
    }
}
