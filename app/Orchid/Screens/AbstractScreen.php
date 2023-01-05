<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;

abstract class AbstractScreen extends Screen
{
    private const DEFAULT_PER_PAGE = 25;

    abstract public static function getIcon(): string;

    abstract public static function getRoute(): string;

    abstract public static function getName(): ?string;

    public static function perPage(): int
    {
        return self::DEFAULT_PER_PAGE;
    }

    public static function getPermissions(): ?array
    {
        return [];
    }

    public function name(): ?string
    {
        return static::getName();
    }

    public function permission(): ?iterable
    {
        return static::getPermissions();
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }
}
