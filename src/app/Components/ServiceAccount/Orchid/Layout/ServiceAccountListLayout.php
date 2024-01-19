<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Orchid\Layout;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Orchid\Enum\ServiceAccountScreenTarget;
use App\Components\ServiceAccount\ServiceAccountServiceProvider;
use Illuminate\View\Factory as ViewFactory;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ServiceAccountListLayout extends Table
{
    /**
     * @var string
     */
    public $target = ServiceAccountScreenTarget::ACCOUNTS_LIST->value;

    private ViewFactory $viewFactory;

    public function __construct(ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('accountName', __('Account name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('serviceName', __('Service'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            $this->buildAccessTokenInfoColumn(),

            TD::make('isActive', __('Is active'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),
        ];
    }

    private function buildAccessTokenInfoColumn(): TD
    {
        $column = TD::make('accessTokenInfo', __('Access information'))
            ->cantHide();

        $column->render(function (ServiceAccount $serviceAccount) use ($column) {
            $tokenButtonView = $this->viewFactory
                ->make(sprintf('%s::account-button', ServiceAccountServiceProvider::RESOURCE_NS), [
                    'serviceAccount' => $serviceAccount,
                ]);

            return $tokenButtonView->render();
        });

        return $column;
    }
}
