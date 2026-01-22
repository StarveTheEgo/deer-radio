<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Components\DoctrineOrchid\Filter\AbstractDoctrineFilter;
use Orchid\Screen\Screen;

abstract class AbstractScreen extends Screen
{
    private const int DEFAULT_PER_PAGE = 25;

    abstract public static function getRoute(): string;

    abstract public static function getName(): ?string;

    public static function perPage(): int
    {
        return self::DEFAULT_PER_PAGE;
    }

    /**
     * @return array<string>|null
     */
    public static function getPermissions(): ?array
    {
        return [];
    }

    #[\Override]
    public function name(): ?string
    {
        return static::getName();
    }

    /**
     * @return iterable<string>|null
     */
    #[\Override]
    public function permission(): ?iterable
    {
        return static::getPermissions();
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<AbstractDoctrineFilter|class-string<AbstractDoctrineFilter>>
     */
    public function filters(): array
    {
        return [];
    }
}
