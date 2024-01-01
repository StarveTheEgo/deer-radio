<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Components\OrchidIntergration\Resolvers\DoctrineAwareScreenDependencyResolver;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orchid\Screen\Screen;
use ReflectionException;

abstract class AbstractScreen extends Screen
{
    private const DEFAULT_PER_PAGE = 25;

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
     * @param string $method
     * @param array<string, mixed>  $httpQueryArguments
     *
     * @throws BindingResolutionException
     * @throws ReflectionException
     *
     * @return array<string, mixed>
     */
    protected function resolveDependencies(string $method, array $httpQueryArguments = []): array
    {
        /** @var DoctrineAwareScreenDependencyResolver $resolver */
        $resolver = app()->make(DoctrineAwareScreenDependencyResolver::class);
        return $resolver->resolveScreen($this, $method, $httpQueryArguments);
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
