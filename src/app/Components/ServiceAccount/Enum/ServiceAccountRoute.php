<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Enum;

enum ServiceAccountRoute: string
{
    case INDEX = 'platform.app.service-account';

    case CREATE = 'platform.app.service-account.create';

    case EDIT = 'platform.app.service-account.edit';

    case OAUTH_REDIRECT = 'platform.app.service-account.oauth-redirect';

    case OAUTH_CALLBACK = 'platform.app.service-account.oauth-callback';

    case OAUTH_DISCONNECT = 'platform.app.service-account.oauth-disconnect';
}
