<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Orchid\Screen;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Enum\ServiceAccountRoute;
use App\Components\ServiceAccount\Orchid\Enum\ServiceAccountPermission;
use App\Components\ServiceAccount\Orchid\Enum\ServiceAccountScreenTarget;
use App\Components\ServiceAccount\Orchid\Layout\ServiceAccountListLayout;
use App\Components\ServiceAccount\Service\ServiceAccountDeleteService;
use App\Components\ServiceAccount\Service\ServiceAccountReadService;
use App\Orchid\Screens\AbstractScreen;
use App\Orchid\Screens\IconAwareInterface;
use DateTimeImmutable;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;

class ServiceAccountIndexScreen extends AbstractScreen implements IconAwareInterface
{
    private ServiceAccountReadService $readService;

    private ServiceAccountDeleteService $deleteService;

    public static function getName(): ?string
    {
        return __('Service accounts');
    }

    public static function getIcon(): string
    {
        return 'share';
    }

    public static function getRoute(): string
    {
        return ServiceAccountRoute::INDEX->value;
    }

    /**
     * @inheritDoc
     */
    public static function getPermissions(): ?array
    {
        return [
            ServiceAccountPermission::CAN_VIEW->value,
        ];
    }

    /**
     * @param ServiceAccountReadService $readService
     * @param ServiceAccountDeleteService $deleteService
     */
    public function __construct(
        ServiceAccountReadService $readService,
        ServiceAccountDeleteService $deleteService
    )
    {
        $this->readService = $readService;
        $this->deleteService = $deleteService;
    }

    public function description(): ?string
    {
        return sprintf(
            __('Accounts with access to 3rd party services. Current time: %s'),
            (new DateTimeImmutable())->format('d.m.Y H:i:s')
        );
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add new account'))
                ->icon('plus')
                ->route(ServiceAccountRoute::CREATE->value),
        ];
    }

    public function query(): iterable
    {
        // @todo consider pagination
        return [
            ServiceAccountScreenTarget::ACCOUNTS_LIST->value => $this->readService->filteredFindAll($this->filters()),
        ];
    }

    public function layout(): iterable
    {
        return [
            ServiceAccountListLayout::class,
        ];
    }

    /**
     * @param ServiceAccount $serviceAccount
     */
    public function delete(ServiceAccount $serviceAccount): void
    {
        $this->deleteService->delete($serviceAccount);

        Toast::info(__('Account was removed'));
    }
}
