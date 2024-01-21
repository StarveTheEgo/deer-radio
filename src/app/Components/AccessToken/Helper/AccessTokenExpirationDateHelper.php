<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Helper;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

class AccessTokenExpirationDateHelper
{
    /** @var int Time window (in seconds) before the actual expiration date, when we will start refreshing tokens */
    public const REFRESH_TIME_WINDOW_START = 5 * 60;

    /**
     * @param int $expiresIn
     * @return DateTimeImmutable
     */
    public function calculateExpirationDateTime(int $expiresIn) : DateTimeImmutable
    {
        Assert::positiveInteger($expiresIn);

        $currentDateTime = new DateTimeImmutable();
        return $currentDateTime
            ->modify(sprintf('+%d seconds', $expiresIn));
    }
}
