<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Factory;

use App\Components\AccessToken\Entity\AccessToken;
use App\Components\ServiceAccount\Enum\ServiceName;
use DateTimeImmutable;
use Webmozart\Assert\Assert;
use Laravel\Socialite\Two\User as OauthV2User;

class AccessTokenFactory
{
    /** @var string Token type */
    private const TOKEN_TYPE = 'Bearer';

    /** @var int Time window in seconds when we consider token invalid, before the actual expiration date */
    private const TOKEN_EXPIRATION_WINDOW = 60;

    /**
     * @param ServiceName $serviceName
     * @param OauthV2User $oauthUser
     * @return AccessToken
     */
    public function createFromOauthV2User(ServiceName $serviceName, OauthV2User $oauthUser) : AccessToken
    {
        return (new AccessToken())
            ->setServiceName($serviceName->value)
            ->setOauthIdentifier((string) $oauthUser->getId())
            ->setTokenType(self::TOKEN_TYPE)
            ->setAuthToken($oauthUser->token)
            ->setRefreshToken($oauthUser->refreshToken)
            ->setExpiresAt($this->calculateExpirationDateTime($oauthUser));
    }

    /**
     * @param AccessToken $accessToken
     * @param OauthV2User $oauthUser
     * @return AccessToken
     */
    public function fillFromOauthV2User(AccessToken $accessToken, OauthV2User $oauthUser) : AccessToken
    {
        return $accessToken
            ->setOauthIdentifier((string) $oauthUser->getId())
            ->setAuthToken($oauthUser->token)
            ->setRefreshToken($oauthUser->refreshToken)
            ->setScopes($oauthUser->approvedScopes)
            ->setExpiresAt($this->calculateExpirationDateTime($oauthUser));
    }

    /**
     * @param OauthV2User $oauthUser
     * @return DateTimeImmutable
     */
    private function calculateExpirationDateTime(OauthV2User $oauthUser) : DateTimeImmutable
    {
        $expiresIn = (int) ($oauthUser->expiresIn - self::TOKEN_EXPIRATION_WINDOW);
        Assert::positiveInteger($expiresIn);

        $currentDateTime = new DateTimeImmutable();
        return $currentDateTime
            ->modify(sprintf('+%d seconds', $expiresIn));
    }
}
