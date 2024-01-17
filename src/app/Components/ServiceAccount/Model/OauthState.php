<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Model;

use JsonSerializable;

class OauthState implements JsonSerializable
{
    private string $nonce;

    private int $serviceAccountId;

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'nonce' => $this->getNonce(),
            'serviceAccountId' => $this->getServiceAccountId(),
        ];
    }

    /**
     * @return string
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * @param string $nonce
     * @return OauthState
     */
    public function setNonce(string $nonce): OauthState
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * @return int
     */
    public function getServiceAccountId(): int
    {
        return $this->serviceAccountId;
    }

    /**
     * @param int $serviceAccountId
     * @return OauthState
     */
    public function setServiceAccountId(int $serviceAccountId): OauthState
    {
        $this->serviceAccountId = $serviceAccountId;
        return $this;
    }
}
