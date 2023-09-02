<?php

declare(strict_types=1);

namespace App\Components\Google;

use App\Components\ComponentData\ComponentDataAccessor;
use App\Components\ComponentData\Service\ComponentDataAccessService;

class GoogleDataAccessor extends ComponentDataAccessor
{
    public function __construct(ComponentDataAccessService $service)
    {
        parent::__construct($service, GoogleServiceProvider::COMPONENT_NAME);
    }
}
