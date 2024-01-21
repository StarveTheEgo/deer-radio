<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Components\AccessToken\Service\AccessTokenReadService;
use App\Components\AccessToken\Service\AccessTokenRefreshService;
use Illuminate\Console\Command;
use ReflectionException;

class RefreshAccessTokens extends Command
{
    protected $signature = 'access-token:refresh';

    protected $description = 'Refreshes expired access tokens';

    /**
     * @throws ReflectionException
     */
    public function handle(): int
    {
        /** @var AccessTokenReadService $readService */
        $readService = app(AccessTokenReadService::class);

        /** @var AccessTokenRefreshService $refreshService */
        $refreshService = app(AccessTokenRefreshService::class);

        foreach ($readService->iterateExpiredRefreshableAccessTokens() as $expiredAccessToken) {
            $refreshService->refreshAccessToken($expiredAccessToken);

            $this->output->writeln(sprintf('Access token #%d is refreshed', $expiredAccessToken->getId()));
            sleep(1);
        }

        return Command::SUCCESS;
    }
}
