<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Factory;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Model\OauthState;
use Exception;
use Webmozart\Assert\Assert;

class OauthStateFactory
{
    /** @var int Amount of bytes for nonce generation (before converting to hex string) */
    private const NONCE_BYTES_LENGTH = 8;

    /**
     * @param array<string, scalar> $input
     * @return OauthState
     */
    public function createFromArray(array $input) : OauthState
    {
        $nonce = $input['nonce'] ?? null;
        Assert::string($nonce);
        Assert::notEmpty($nonce);

        $serviceAccountId = $input['serviceAccountId'] ?? null;
        Assert::integer($serviceAccountId);

        return (new OauthState())
            ->setNonce($nonce)
            ->setServiceAccountId($serviceAccountId);
    }

    /**
     * Generates random state to be used for processing later (during callback request processing)
     * @param ServiceAccount $serviceAccount
     * @return OauthState
     * @throws Exception
     */
    public function generateRandomState(ServiceAccount $serviceAccount) : OauthState
    {
        return (new OauthState())
            ->setNonce($this->generateNonce())
            ->setServiceAccountId($serviceAccount->getId());
    }

    /**
     * @return string
     * @throws Exception
     */
    private function generateNonce(): string
    {
        $bytes = random_bytes(self::NONCE_BYTES_LENGTH);

        return bin2hex($bytes);
    }
}
