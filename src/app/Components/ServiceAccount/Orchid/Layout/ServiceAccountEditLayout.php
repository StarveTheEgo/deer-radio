<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Orchid\Layout;

use App\Components\OrchidIntergration\Helper\PrefixHelper;
use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Enum\ServiceName;
use App\Components\ServiceAccount\Orchid\Enum\ServiceAccountScreenTarget;
use App\Components\ServiceAccount\ServiceAccountServiceProvider;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Rows;

class ServiceAccountEditLayout extends Rows
{
    /**
     * @return iterable
     */
    protected function fields(): iterable
    {
        $prefix = sprintf(
            '%s.',
            ServiceAccountScreenTarget::CURRENT_ACCOUNT->value
        );

        return PrefixHelper::addPrefixToFields($prefix, [
            Input::make('id')
                ->type('hidden')
                ->required(),

            Input::make('accountName')
                ->title(__('Account name'))
                ->placeholder('Account name')
                ->help('Name of 3-rd party service account to be referred later')
                ->required(),

            Select::make('serviceName')
                ->title('Service')
                ->options($this->getServiceNameSelectOptions())
                ->required(),

            // -*/*- Tyson's take
            ViewField::make('accessTokenInfo')
                ->view(sprintf('%s::access-token-info', ServiceAccountServiceProvider::RESOURCE_NS))
                ->set('serviceAccount', $this->getCurrentServiceAccount()),

            Switcher::make('isActive')
                ->sendTrueOrFalse()
                ->title(__('Is active'))
                ->help(__('This account will be used in the project. Access tokens will be auto-updated.'))
                ->value(1),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function getServiceNameSelectOptions(): array
    {
        $options = [];
        foreach (ServiceName::cases() as $serviceName) {
            $options[$serviceName->value] = $serviceName->title();
        }

        return $options;
    }

    /**
     * @return ServiceAccount|null
     */
    private function getCurrentServiceAccount(): ?ServiceAccount
    {
        /** @var ServiceAccount|null $currentAccount */
        $currentAccount = $this->query[ServiceAccountScreenTarget::CURRENT_ACCOUNT->value];
        return $currentAccount;
    }
}
