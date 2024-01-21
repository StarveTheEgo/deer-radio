<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Service;

use App\Components\AccessToken\Entity\AccessToken;
use App\Components\AccessToken\Helper\AccessTokenExpirationDateHelper;
use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\Two\AbstractProvider;
use ReflectionClass;
use ReflectionException;
use Webmozart\Assert\Assert;

class AccessTokenRefreshService
{
    private SocialiteManager $socialiteManager;

    private AccessTokenUpdateService $accessTokenUpdateService;

    private AccessTokenExpirationDateHelper $expirationDateHelper;

    /**
     * @param SocialiteManager $socialiteManager
     * @param AccessTokenUpdateService $accessTokenUpdateService
     * @param AccessTokenExpirationDateHelper $expirationDateHelper
     */
    public function __construct(
        SocialiteManager $socialiteManager,
        AccessTokenUpdateService $accessTokenUpdateService,
        AccessTokenExpirationDateHelper $expirationDateHelper
    )
    {
        $this->socialiteManager = $socialiteManager;
        $this->accessTokenUpdateService = $accessTokenUpdateService;
        $this->expirationDateHelper = $expirationDateHelper;
    }

    /**
     * @param AccessToken $accessToken
     * @throws ReflectionException
     */
    public function refreshAccessToken(AccessToken $accessToken): void
    {
        $refreshToken = $accessToken->getRefreshToken();
        Assert::notNull($refreshToken);

        /** @var AbstractProvider $provider */
        $provider = $this->socialiteManager->driver($accessToken->getServiceName());

        // Socialite's method getRefreshToken is not working when refresh_token is empty in response
        // we will use reflection to call a method that works
        $response = (new ReflectionClass($provider))
            ->getMethod('getRefreshTokenResponse')
            ->invoke($provider, $refreshToken);

        Assert::keyExists($response, 'access_token');
        Assert::keyExists($response, 'expires_in');

        $accessToken->setAuthToken($response['access_token']);
        $accessToken->setExpiresAt($this->expirationDateHelper->calculateExpirationDateTime($response['expires_in']));
        if (array_key_exists('refresh_token', $response)) {
            $accessToken->setRefreshToken($response['refresh_token']);
        }

        $this->accessTokenUpdateService->update($accessToken);
    }
}
