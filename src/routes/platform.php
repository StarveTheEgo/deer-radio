<?php

declare(strict_types=1);

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\Output\Enum\OutputRoute;
use App\Components\Output\Orchid\Screen\OutputEditScreen;
use App\Components\Output\Orchid\Screen\OutputIndexScreen;
use App\Components\Setting\Orchid\Screen\SettingScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('User'), route('platform.systems.users.edit', $user));
    });

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

// Platform -> Settings
Route::screen('settings/{group?}', SettingScreen::class)
    ->name(SettingScreen::getRoute());

// Platform -> Edit
Route::screen('outputs/{output}/edit', OutputEditScreen::class)
    ->name(OutputRoute::EDIT->value)
    ->breadcrumbs(function (Trail $trail, ?AbstractDomainObject $output) {
        return $trail
            ->parent(OutputRoute::INDEX->value)
            ->push(__('Edit output'), route(OutputRoute::EDIT->value, $output?->getId()));
    });

// Platform -> Create
Route::screen('outputs/create', OutputEditScreen::class)
    ->name(OutputRoute::CREATE->value)
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent(OutputRoute::INDEX->value)
            ->push(__('Create output'), route(OutputRoute::CREATE->value));
    });

// Platform -> Outputs
Route::screen('outputs', OutputIndexScreen::class)
    ->name(OutputRoute::INDEX->value)
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Outputs'), route(OutputRoute::INDEX->value));
    });

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Example screen');
    });
