<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Resolver;

use App\Components\ServiceAccount\Enum\ServiceName;
use Google\Service\YouTube;

class ServiceScopeResolver
{
    /**
     * @param ServiceName $serviceName
     * @return array<string>|null
     */
    public function resolve(ServiceName $serviceName) : ?array
    {
        if ($serviceName === ServiceName::GOOGLE) {
            return [
                YouTube::YOUTUBE,
            ];
        }

        return null;
    }
}
