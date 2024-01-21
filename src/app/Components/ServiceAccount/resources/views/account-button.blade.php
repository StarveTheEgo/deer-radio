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
    $tokenExists = ($accessToken !== null);

    $isExpired = false;
    if ($tokenExists) {
        $expirationDate = $accessToken->getExpiresAt();
        if ($expirationDate !== null) {
            $currentDate = new DateTimeImmutable();
            $isExpired = ($expirationDate <= $currentDate);
        }
    }

    $showConnectButton = (!$tokenExists || $isExpired);
    $connectButtonText = $tokenExists ? 'Reconnect' : 'Connect';

    $serviceAccountId = $serviceAccount->getId();
    $connectRoute = route(ServiceAccountRoute::OAUTH_REDIRECT->value, ['serviceAccount' => $serviceAccountId]);
    $disconnectRoute = route(ServiceAccountRoute::OAUTH_DISCONNECT->value, ['serviceAccount' => $serviceAccountId]);

    $tokenInfoViewName = sprintf('%s::access-token-info', ServiceAccountServiceProvider::RESOURCE_NS);
@endphp

@include($tokenInfoViewName)

<div class="form-group">
    @if ($showConnectButton)
        <a class="btn btn-primary btn-lg" href="{{ $connectRoute }}" role="button">{{ $connectButtonText }}</a>
    @endif

    @if ($tokenExists)
        <a class="btn btn-danger btn-lg" href="{{ $disconnectRoute }}" role="button">Disconnect</a>
    @endif
</div>
