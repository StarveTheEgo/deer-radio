@php
    use App\Components\ServiceAccount\Entity\ServiceAccount;
    use App\Components\ServiceAccount\Enum\ServiceAccountRoute;
    use App\Components\ServiceAccount\ServiceAccountServiceProvider;

    /** @var ServiceAccount $serviceAccount */
    if ($serviceAccount === null) {
        return;
    }

    /** @var ServiceAccount $serviceAccount */
    $accessToken = $serviceAccount->getAccessToken();

    $tokenInfoViewName = sprintf('%s::access-token-info', ServiceAccountServiceProvider::RESOURCE_NS);
@endphp

@include($tokenInfoViewName)

<div class="form-group">
    @if ($accessToken === null)
        <a class="btn btn-primary btn-lg"
           href="{{ route(ServiceAccountRoute::OAUTH_REDIRECT->value, ['serviceAccount' => $serviceAccount->getId()]) }}"
           role="button">
            Connect
        </a>
    @else
        <a class="btn btn-primary btn-lg"
           href="{{ route(ServiceAccountRoute::OAUTH_DISCONNECT->value, ['serviceAccount' => $serviceAccount->getId()]) }}"
           role="button">
            Disconnect
        </a>
    @endif
</div>
