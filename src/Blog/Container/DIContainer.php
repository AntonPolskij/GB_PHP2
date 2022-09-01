<?php

namespace GeekBrains\LevelTwo\Blog\Container;

use GeekBrains\LevelTwo\Exceptions\NotFoundException;
use ReflectionClass;
use Psr\Container\ContainerInterface;



class DIContainer implements ContainerInterface
{
    // Массив правил создания объектов
    private array $resolvers = [];

    // Метод для добавления правил
    public function bind(string $type, $resolver)
    {
        $this->resolvers[$type] = $resolver;
    }

    /**
     * @throws NotFoundException
     */
    public function get(string $type): object
    {

        if (array_key_exists($type, $this->resolvers)) {

            $typeToCreate = $this->resolvers[$type];

            // Если в контейнере для запрашиваемого типа
// уже есть готовый объект — возвращаем его
            if (is_object($typeToCreate)) {
                return $typeToCreate;
            }


            return $this->get($typeToCreate);
        }
        if (!class_exists($type)) {
            throw new NotFoundException("Cannot resolve type: $type");
        }
        $reflectionClass = new ReflectionClass($type);

        $constructor = $reflectionClass->getConstructor();

        if (null === $constructor) {
            return new $type();
        }
        $parameters = [];

        foreach ($constructor->getParameters() as $parameter) {
            $parameterType = $parameter->getType()->getName();
            $parameters[] = $this->get($parameterType);
        }
        return new $type(...$parameters);
    }

    public function has(string $id): bool
    {
        try {
            $this->get($id);
        } catch (NotFoundException $e) {

            return false;
        }

        return true;
    }
}