<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Resolvers;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\Exceptions\ObjectNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Illuminate\Contracts\Routing\UrlRoutable as LaravelUrlRoutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use LaravelDoctrine\ORM\Contracts\UrlRoutable as DoctrineUrlRoutable;
use Orchid\Screen\Resolvers\ScreenDependencyResolver;
use Orchid\Screen\Screen;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use Throwable;

/**
 * "Patched" version of ScreenDependencyResolver to support Doctrine route bindings
 * @see ScreenDependencyResolver
 */
class DoctrineAwareScreenDependencyResolver
{
    /**
     * @var ManagerRegistry
     */
    protected ManagerRegistry $entityManagerRegistry;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->entityManagerRegistry = $registry;
    }

    /**
     * Resolves arguments for specified method of the screen
     * @param Screen $screen
     * @param string $method
     * @param array<string, mixed> $httpQueryArguments
     *
     * @throws ReflectionException
     *
     * @return array<string, mixed>
     */
    public function resolveScreen(Screen $screen, string $method, array $httpQueryArguments = []): array
    {
        $parameters = (new ReflectionClass($screen))->getMethod($method)->getParameters();

        $httpQueryArgumentsCollection = collect($httpQueryArguments);
        $currentRoute = Route::current();

        return collect($parameters)
            ->map(function (ReflectionParameter $parameter) use ($httpQueryArgumentsCollection, $currentRoute) {
                $resolvedValue = $this->resolveCurrentValue($parameter, $httpQueryArgumentsCollection);

                $currentRoute?->setParameter($parameter->getName(), $resolvedValue);

                return $resolvedValue;
            })
            ->all();
    }

    /**
     * Resolves value for specified parameter
     * @param ReflectionParameter $parameter
     * @param Collection $httpQueryArgumentsCollection
     * @return mixed
     * @throws Throwable
     */
    private function resolveCurrentValue(ReflectionParameter $parameter, Collection $httpQueryArgumentsCollection): mixed
    {
        $parameterClassName = $this->getClassName($parameter);

        if ($parameterClassName === null) {
            // built-in / non-specified parameter type
            return $httpQueryArgumentsCollection->shift();
        }

        $parameterClass = new ReflectionClass($parameterClassName);

        if ($parameterClass->isSubclassOf(AbstractDomainObject::class)) {
            // is a Doctrine object
            $repository = $this->entityManagerRegistry->getRepository($parameterClassName);
            $parameterValue = $httpQueryArgumentsCollection->shift();
            $resolvedObject = $this->resolveDoctrineEntity($repository, $parameterClass, $parameterValue);

            throw_if(
                $resolvedObject === null && !$parameter->isDefaultValueAvailable(),
                (new ObjectNotFoundException())->setObjectInfo($parameterClass->getName(), [$parameterValue])
            );
        } else {
            // possible is a Laravel model
            $resolvedObject = resolve($parameterClass->getName());
            if (!is_a($resolvedObject, LaravelUrlRoutable::class)) {
                // is not a Laravel model
                return $resolvedObject;
            }

            $parameterValue = $httpQueryArgumentsCollection->shift();
            if ($parameterValue === null) {
                // value is null, - returning uninitialized model
                return $resolvedObject;
            }

            $resolvedObject = $resolvedObject->resolveRouteBinding($parameterValue);
            throw_if(
                $resolvedObject === null && !$parameter->isDefaultValueAvailable(),
                (new ModelNotFoundException())->setModel($parameterClass, [$parameterValue])
            );
        }

        return $resolvedObject;
    }

    /**
     * Resolved specified Doctrine entity
     * @param ObjectRepository $repository
     * @param ReflectionClass $parameterClass
     * @param $parameterValue
     * @return object|null
     */
    private function resolveDoctrineEntity(ObjectRepository $repository, ReflectionClass $parameterClass, $parameterValue) : ?object
    {
        if ($parameterClass->implementsInterface(DoctrineUrlRoutable::class)) {
            $name = call_user_func([$parameterClass->getName(), 'getRouteKeyName']);

            $entity = $repository->findOneBy([
                $name => $parameterValue
            ]);
        } else {
            $entity = $repository->find($parameterValue);
        }

        return $entity;
    }

    /**
     * Fetches class name of parameter, if its class name is specified
     * @param ReflectionParameter $parameter
     * @return string|null
     */
    private function getClassName(ReflectionParameter $parameter): ?string
    {
        if (($type = $parameter->getType()) && $type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
            return $type->getName();
        }

        return null;
    }
}
