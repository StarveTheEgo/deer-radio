<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Filler;

use App\Components\ServiceAccount\Entity\ServiceAccount;

class ServiceAccountFiller
{
    /**
     * Fills service account object from input data
     * @param ServiceAccount $serviceAccount
     * @param array<string, mixed> $input
     * @return ServiceAccount
     */
    public function fillFromArray(ServiceAccount $serviceAccount, array $input) : ServiceAccount
    {
        return $serviceAccount
            ->setAccountName($input['accountName'])
            ->setServiceName($input['serviceName'])
            ->setIsActive((bool) $input['isActive']);
    }
}
