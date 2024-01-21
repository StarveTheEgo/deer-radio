@php
    use App\Components\ServiceAccount\Entity\ServiceAccount;

    /** @var ServiceAccount $serviceAccount */
    if ($serviceAccount === null) {
        return;
    }

    $accessToken = $serviceAccount->getAccessToken();

    if ($accessToken !== null) {
        $currentDate = new DateTimeImmutable();
        $expirationDate = $accessToken->getExpiresAt();
        if ($currentDate < $expirationDate) {
            $class = 'text-success';
            $status = 'Connected';
        } else {
            $class = 'text-danger';
            $status = 'Expired';
        }
    } else {
        $expirationDate = null;
        $class = 'text-muted';
        $status = 'Not connected';
    }
@endphp

<div class="form-group">
    <label class="form-label">
        Status
    </label>
    <div>
        <p>
            <span class="{{ $class }}">
                {{ $status }}
            </span>
        </p>
        @if ($expirationDate !== null)
            <p>
                Access expiration date: {{ $expirationDate->format('d.m.Y H:i:s') }}
            </p>
        @endif
    </div>
</div>
