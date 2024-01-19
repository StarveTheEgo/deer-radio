<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Http\Controllers;

use App\Components\AccessToken\Factory\AccessTokenFactory;
use App\Components\AccessToken\Service\AccessTokenCreateService;
use App\Components\AccessToken\Service\AccessTokenDeleteService;
use App\Components\AccessToken\Service\AccessTokenUpdateService;
use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Enum\ServiceAccountRoute;
use App\Components\ServiceAccount\Enum\ServiceName;
use App\Components\ServiceAccount\Factory\OauthStateFactory;
use App\Components\ServiceAccount\Model\OauthState;
use App\Components\ServiceAccount\Resolver\ServiceScopeResolver;
use App\Components\ServiceAccount\Service\ServiceAccountReadService;
use App\Components\ServiceAccount\Service\ServiceAccountUpdateService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Session\Store as SessionStorage;
use JsonException;
use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\Two\AbstractProvider;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    private AccessTokenDeleteService $accessTokenDeleteService;

    private OauthStateFactory $stateFactory;

    private AccessTokenFactory $accessTokenFactory;

    private SessionStorage $sessionStorage;

    private ServiceScopeResolver $serviceScopeResolver;

    /**
     * @param ResponseFactory $responseFactory
     * @param SocialiteManager $socialiteManager
     * @param ServiceAccountUpdateService $serviceAccountUpdateService
     * @param ServiceAccountReadService $serviceAccountReadService
     * @param AccessTokenCreateService $accessTokenCreateService
     * @param AccessTokenUpdateService $accessTokenUpdateService
     * @param AccessTokenDeleteService $accessTokenDeleteService
     * @param OauthStateFactory $stateFactory
     * @param AccessTokenFactory $accessTokenFactory
     * @param SessionStorage $sessionStorage
     * @param ServiceScopeResolver $serviceScopeResolver
     */
    public function __construct(
        ResponseFactory $responseFactory,
        SocialiteManager $socialiteManager,
        ServiceAccountUpdateService $serviceAccountUpdateService,
        ServiceAccountReadService $serviceAccountReadService,
        AccessTokenCreateService $accessTokenCreateService,
        AccessTokenUpdateService $accessTokenUpdateService,
        AccessTokenDeleteService $accessTokenDeleteService,
        OauthStateFactory $stateFactory,
        AccessTokenFactory $accessTokenFactory,
        SessionStorage $sessionStorage,
        ServiceScopeResolver $serviceScopeResolver
    )
    {
        $this->responseFactory = $responseFactory;
        $this->socialiteManager = $socialiteManager;
        $this->serviceAccountReadService = $serviceAccountReadService;
        $this->serviceAccountUpdateService = $serviceAccountUpdateService;
        $this->accessTokenCreateService = $accessTokenCreateService;
        $this->accessTokenUpdateService = $accessTokenUpdateService;
        $this->accessTokenDeleteService = $accessTokenDeleteService;
        $this->stateFactory = $stateFactory;
        $this->accessTokenFactory = $accessTokenFactory;
        $this->sessionStorage = $sessionStorage;
        $this->serviceScopeResolver = $serviceScopeResolver;
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

        // resolve additional scopes
        $additionalScopes = $this->serviceScopeResolver->resolve($serviceName);

        return $this
            ->getOauthProvider($serviceName)
            ->stateless() // we will validate the state manually
            ->with([
                'access_type' => 'offline',
                'prompt' => implode(' ', [
                    'consent',
                    'select_account',
                ]),
                'state' => json_encode($state, flags: JSON_THROW_ON_ERROR),
            ])
            ->scopes($additionalScopes ?? [])
            ->redirect();
    }

    /**
     * @param Request $request
     * @param string $serviceNameValue
     * @return RedirectResponse
     * @throws JsonException
     */
    public function callback(Request $request, string $serviceNameValue) : RedirectResponse
    {
        $serviceName = ServiceName::from($serviceNameValue);
        $oauthProvider = $this
            ->getOauthProvider($serviceName)
            ->stateless();

        // get OauthV2 user
        $oauthUser = $oauthProvider->user();

        // load and check stored state
        $storedState = $this->loadSessionOauthState($serviceName);
        Assert::notNull($storedState);
        $storedStateSerialized = json_encode($storedState, flags: JSON_THROW_ON_ERROR);

        // get input state from request
        $inputStateSerialized = $request->get('state');
        Assert::notNull($inputStateSerialized);

        // validate input state
        Assert::eq($inputStateSerialized, $storedStateSerialized);

        // input state is valid - can proceed to managing the data
        $serviceAccount = $this->serviceAccountReadService->getById($storedState->getServiceAccountId());

        $accessToken = $serviceAccount->getAccessToken();
        $isNewAccessToken = ($accessToken === null);

        if ($isNewAccessToken) {
            $accessToken = $this->accessTokenFactory->createFromOauthV2User($serviceName, $oauthUser);
        } else {
            $accessToken = $this->accessTokenFactory->fillFromOauthV2User($accessToken, $oauthUser);
        }
        Assert::eq($accessToken->getServiceName(), $serviceAccount->getServiceName());

        if ($isNewAccessToken) {
            $this->accessTokenCreateService->create($accessToken);
        } else {
            $this->accessTokenUpdateService->update($accessToken);
        }

        $serviceAccount->setAccessToken($accessToken);
        $this->serviceAccountUpdateService->update($serviceAccount);

        return $this->responseFactory->redirectToRoute(ServiceAccountRoute::INDEX->value);
    }

    /**
     * @param ServiceAccount $serviceAccount
     * @return RedirectResponse
     */
    public function disconnect(ServiceAccount $serviceAccount) : RedirectResponse
    {
        $accessToken = $serviceAccount->getAccessToken();
        Assert::notNull($accessToken);

        $serviceAccount->setAccessToken(null);
        $this->serviceAccountUpdateService->update($serviceAccount);
        $this->accessTokenDeleteService->delete($accessToken);

        Toast::info(__('Account was disconnected.'));

        return $this->responseFactory->redirectToRoute(ServiceAccountRoute::INDEX->value);
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
