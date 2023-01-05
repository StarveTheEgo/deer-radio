<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Components\Setting\Orchid\Screen\SettingScreen;
use App\Orchid\Screens\AbstractScreen;
use InvalidArgumentException;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @var AbstractScreen[]
     */
    private const PERMISSION_AWARE_SCREENS = [
        SettingScreen::class,
    ];

    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make('Example screen')
                ->icon('monitor')
                ->route('platform.example')
                ->title('Navigation')
                ->badge(function () {
                    return 6;
                }),

            Menu::make('Dropdown menu')
                ->icon('code')
                ->list([
                    Menu::make('Sub element item 1')->icon('bag'),
                    Menu::make('Sub element item 2')->icon('heart'),
                ]),

            Menu::make('Basic Elements')
                ->title('Form controls')
                ->icon('note')
                ->route('platform.example.fields'),

            Menu::make('Advanced Elements')
                ->icon('briefcase')
                ->route('platform.example.advanced'),

            Menu::make('Text Editors')
                ->icon('list')
                ->route('platform.example.editors'),

            Menu::make('Overview layouts')
                ->title('Layouts')
                ->icon('layers')
                ->route('platform.example.layouts'),

            Menu::make('Chart tools')
                ->icon('bar-chart')
                ->route('platform.example.charts'),

            Menu::make('Cards')
                ->icon('grid')
                ->route('platform.example.cards')
                ->divider(),

            Menu::make('Documentation')
                ->title('Docs')
                ->icon('docs')
                ->url('https://orchid.software/en/docs'),

            Menu::make('Changelog')
                ->icon('shuffle')
                ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                ->target('_blank')
                ->badge(function () {
                    return Dashboard::version();
                }, Color::DARK()),

            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access rights')),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),

            $this->makeRegisteredMenuFor(SettingScreen::class),
        ];
    }

    private function makeRegisteredMenuFor(string $screen): Link|Menu
    {
        if (!is_subclass_of($screen, AbstractScreen::class)) {
            throw new InvalidArgumentException(sprintf('Screen class must be inherited from %s, got: %s', AbstractScreen::class, $screen));
        }

        return Menu::make($screen::getName())
            ->icon($screen::getIcon())
            ->route($screen::getRoute())
            ->permission($screen::getPermissions());
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        $project_group = ItemPermission::group(__('Project'));
        foreach (self::PERMISSION_AWARE_SCREENS as $screen_class) {
            $screen_name = $screen_class::getName();
            $permissions = $screen_class::getPermissions();
            $permissions_count = count($permissions);

            foreach ($screen_class::getPermissions() as $permission) {
                $permission_name = $screen_name;
                if ($permissions_count > 1) {
                    $permission_name .= sprintf('[%s]', $permission);
                }
                $project_group->addPermission($permission, $permission_name);
            }
        }

        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),

            $project_group,
        ];
    }
}
