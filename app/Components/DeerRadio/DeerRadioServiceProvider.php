<?php

declare(strict_types=1);

namespace App\Components\DeerRadio;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeerRadioServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public const COMPONENT_NAME = 'DeerRadio';
}
