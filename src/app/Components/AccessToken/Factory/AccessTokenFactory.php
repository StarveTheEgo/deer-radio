<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Factory;

use App\Components\AccessToken\Entity\AccessToken;
use App\Components\AccessToken\Helper\AccessTokenExpirationDateHelper;
use App\Components\ServiceAccount\Enum\ServiceName;
use Laravel\Socialite\Two\User as OauthV2User;

class AccessTokenFactory
{
    /** @var string Token type */
    private const TOKEN_TYPE = 'Bearer';

    private AccessTokenExpirationDateHelper $expirationDateHelper;

    /**
     * @param AccessTokenExpirationDateHelper $expirationDateHelper
     */
    public function __construct(AccessTokenExpirationDateHelper $expirationDateHelper)
    {
        $this->expirationDateHelper = $expirationDateHelper;
    }

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
            ->setExpiresAt($this->expirationDateHelper->calculateExpirationDateTime($oauthUser->expiresIn));
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
            ->setExpiresAt($this->expirationDateHelper->calculateExpirationDateTime($oauthUser->expiresIn));
    }
}
