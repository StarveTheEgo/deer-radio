<?php

declare(strict_types=1);

namespace App\Components\Liquidsoap;

use App\Components\Liquidsoap\Api\LiquidsoapApi;
use App\Components\Liquidsoap\Enum\LiquidsoapConfigKey;
use App\Components\Liquidsoap\Factory\LiquidsoapHttpClientFactory;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class LiquidsoapServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var array<class-string>
     */
    public array $singletons = [
        LiquidsoapApi::class,
    ];

    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(LiquidsoapHttpClientFactory::class, function () {
            /** @var ConfigRepository $configRepository */
            $configRepository = $this->app->get('config');

            return new LiquidsoapHttpClientFactory(
                $configRepository->get(LiquidsoapConfigKey::URL->value)
            );
        });
    }

    /**
     * @return array<class-string>
     */
    public function provides(): array
    {
        return [
            LiquidsoapApi::class,
            LiquidsoapHttpClientFactory::class,
        ];
    }
}
