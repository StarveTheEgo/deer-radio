<?php

declare(strict_types=1);

namespace App\Components\DoctrineOrchid\Helper;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Illuminate\Container\Container;
use Illuminate\Routing\Route;
use LaravelDoctrine\ORM\Contracts\UrlRoutable as DoctrineUrlRoutable;
use Orchid\Screen\Screen;

class DoctrineRouteParameterResolverHelper
{
    public static function getParameterResolverCallback(): callable
    {
        return function (Container $container, Route $route, callable $default) {
            $class = $route->getControllerClass();
            $methodName = $route->getActionMethod();
            if (!is_a($class, Screen::class, true) || !method_exists($class, $methodName)) {
                return $default($container, $route, $default);
            }

            $parameters = $route->parameters();
            $reflectionMethod = new \ReflectionMethod($class, $methodName);
            foreach ($reflectionMethod->getParameters() as $parameter) {
                $parameterType = $parameter->getType();
                if (!($parameterType instanceof \ReflectionNamedType) || $parameterType->isBuiltin()) {
                    continue;
                }

                $parameterClass = $parameter->getType()->getName();
                if (!is_a($parameterClass, AbstractDomainObject::class, true)) {
                    continue;
                }

                $parameterValue = $parameters[$parameter->getName()] ?? throw new \LogicException('Parameter "' . $parameter->getName() . '" of type "' . $parameterClass . '" does not have value.');

                $repository = $container->get(EntityManagerInterface::class)->getRepository($parameterClass);
                $resolvedEntity = self::resolveDoctrineEntity($repository, $parameterClass, $parameterValue);

                $route->setParameter($parameter->getName(), $resolvedEntity);
            }

            return $default($container, $route, $default);
        };
    }

    /**
     * Resolved specified Doctrine entity
     * @param ObjectRepository $repository
     * @param string $parameterClass
     * @param $parameterValue
     * @return object|null
     * @throws \ReflectionException
     */
    private static function resolveDoctrineEntity(ObjectRepository $repository, string $parameterClass, $parameterValue) : ?object
    {
        $reflectionClass = new \ReflectionClass($parameterClass);

        if ($reflectionClass->implementsInterface(DoctrineUrlRoutable::class)) {
            $name = call_user_func([$reflectionClass->getName(), 'getRouteKeyNameStatic']);

            $entity = $repository->findOneBy([
                $name => $parameterValue
            ]);
        } else {
            $entity = $repository->find($parameterValue);
        }

        return $entity;
    }
}
