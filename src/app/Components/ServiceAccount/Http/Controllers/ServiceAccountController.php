<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Http\Controllers;

use App\Components\AccessToken\Factory\AccessTokenFactory;
use App\Components\AccessToken\Service\AccessTokenCreateService;
use App\Components\AccessToken\Service\AccessTokenUpdateService;
use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Enum\ServiceName;
use App\Components\ServiceAccount\Factory\OauthStateFactory;
use App\Components\ServiceAccount\Model\OauthState;
use App\Components\ServiceAccount\Service\ServiceAccountReadService;
use App\Components\ServiceAccount\Service\ServiceAccountUpdateService;
use App\Http\Controllers\Controller;
use Exception;
use Google\Service\YouTube;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Session\Store as SessionStorage;
use JsonException;
use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\Two\AbstractProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class ServiceAccountController extends Controller
{
    private const OAUTH_STATE_SESSION_KEY_PREFIX = 'ServiceAccountOauthState';

    private ResponseFactory $responseFactory;

    private SocialiteManager $socialiteManager;

    private ServiceAccountReadService $serviceAccountReadService;

    private ServiceAccountUpdateService $serviceAccountUpdateService;

    private AccessTokenCreateService $accessTokenCreateService;

    private AccessTokenUpdateService $accessTokenUpdateService;

    private OauthStateFactory $stateFactory;

    private AccessTokenFactory $accessTokenFactory;

    private SessionStorage $sessionStorage;

    /**
     * @param ResponseFactory $responseFactory
     * @param SocialiteManager $socialiteManager
     * @param ServiceAccountUpdateService $serviceAccountUpdateService
     * @param ServiceAccountReadService $serviceAccountReadService
     * @param AccessTokenCreateService $accessTokenCreateService
     * @param AccessTokenUpdateService $accessTokenUpdateService
     * @param OauthStateFactory $stateFactory
     * @param AccessTokenFactory $accessTokenFactory
     * @param SessionStorage $sessionStorage
     */
    public function __construct(
        ResponseFactory $responseFactory,
        SocialiteManager $socialiteManager,
        ServiceAccountUpdateService $serviceAccountUpdateService,
        ServiceAccountReadService $serviceAccountReadService,
        AccessTokenCreateService $accessTokenCreateService,
        AccessTokenUpdateService $accessTokenUpdateService,
        OauthStateFactory $stateFactory,
        AccessTokenFactory $accessTokenFactory,
        SessionStorage $sessionStorage
    )
    {
        $this->responseFactory = $responseFactory;
        $this->socialiteManager = $socialiteManager;
        $this->serviceAccountReadService = $serviceAccountReadService;
        $this->serviceAccountUpdateService = $serviceAccountUpdateService;
        $this->accessTokenCreateService = $accessTokenCreateService;
        $this->accessTokenUpdateService = $accessTokenUpdateService;
        $this->stateFactory = $stateFactory;
        $this->accessTokenFactory = $accessTokenFactory;
        $this->sessionStorage = $sessionStorage;
    }

    /**
     * @param ServiceName $serviceName
     * @param OauthState $state
     * @return void
     */
    private function storeSessionOauthState(ServiceName $serviceName, OauthState $state) : void
    {
        $sessionKey = $this->getSessionStateKey($serviceName);
        $this->sessionStorage->put($sessionKey, $state);
    }

    /**
     * @param ServiceName $serviceName
     * @return OauthState|null
     */
    private function loadSessionOauthState(ServiceName $serviceName) : ?OauthState
    {
        $sessionKey = $this->getSessionStateKey($serviceName);
        return $this->sessionStorage->get($sessionKey);
    }

    /**
     * @param ServiceName $serviceName
     * @return string
     */
    private function getSessionStateKey(ServiceName $serviceName): string
    {
        return sprintf(
            '%s.%s',
            self::OAUTH_STATE_SESSION_KEY_PREFIX,
            $serviceName->value
        );
    }

    /**
     * @param ServiceAccount $serviceAccount
     * @return RedirectResponse
     * @throws JsonException
     * @throws Exception
     */
    public function redirect(ServiceAccount $serviceAccount) : RedirectResponse
    {
        $serviceName = ServiceName::from($serviceAccount->getServiceName());

        // build the state and store it in the session storage
        $state = $this->stateFactory->generateRandomState($serviceAccount);
        $this->storeSessionOauthState($serviceName, $state);

        return $this
            ->getOauthProvider($serviceName)
            ->with([
                'access_type' => 'offline',
                'prompt' => implode(' ', [
                    'consent',
                    'select_account',
                ]),
                'state' => json_encode($state, flags: JSON_THROW_ON_ERROR),
            ])
            ->scopes([
                YouTube::YOUTUBE, // @todo scopes in the entity
            ])
            ->redirect();
    }

    /**
     * @param ServiceName $serviceName
     * @return Response
     * @throws JsonException
     */
    public function callback(ServiceName $serviceName) : Response
    {
        // get OauthV2 user
        $oauthProvider = $this->getOauthProvider($serviceName);
        $oauthUser = $oauthProvider->user();

        // load and check stored state
        $storedState = $this->loadSessionOauthState($serviceName);
        Assert::notNull($storedState);
        $storedStateSerialized = json_encode($storedState, flags: JSON_THROW_ON_ERROR);

        // get input state from request
        $inputStateSerialized = $oauthUser->getRaw()['state'] ?? null;
        Assert::notNull($inputStateSerialized);

        // validate input state
        Assert::eq($inputStateSerialized, $storedStateSerialized);

        // input state is valid - can proceed to managing the data
        $serviceAccount = $this->serviceAccountReadService->getById($storedState->getServiceAccountId());

        $accessToken = $serviceAccount->getAccessToken();
        if ($accessToken !== null) {
            $accessToken = $this->accessTokenFactory->fillFromOauthV2User($accessToken, $oauthUser);
            $this->accessTokenUpdateService->update($accessToken);
        } else {
            $accessToken = $this->accessTokenFactory->createFromOauthV2User($serviceName, $oauthUser);
            $this->accessTokenCreateService->create($accessToken);
        }
        $this->serviceAccountUpdateService->update($serviceAccount);

        return $this->responseFactory->noContent();
    }

    /**
     * @param ServiceName $serviceName
     * @return AbstractProvider
     */
    private function getOauthProvider(ServiceName $serviceName) : AbstractProvider
    {
        return $this->socialiteManager->driver($serviceName->value);
    }
}
