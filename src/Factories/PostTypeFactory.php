<?php

namespace WDM\PluginComponents\Factories;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use WDM\PluginComponents\Entities\PostTypeEntity;
use ReflectionClass;
use ReflectionException;
use stdClass;

class PostTypeFactory
{
    /**
     * @param Container $container
     * @param array $properties
     * @return PostTypeEntity
     * @throws DependencyException
     * @throws NotFoundException|ReflectionException
     */
    public function __invoke(Container $container, array $properties): PostTypeEntity
    {
        $postType = $container->get(PostTypeEntity::class);
        $this->clearPostTypeEntity($postType);

        foreach ($properties as $property => $value){
            $property = 'set' . ucfirst($property);
            $postType->$property($value);
        }

        return $postType;
    }

    /**
     * Clear the exiting postTypeEntity before create another one
     *
     * @param PostTypeEntity $postType
     * @return void
     * @throws ReflectionException
     */
    private function clearPostTypeEntity(PostTypeEntity $postType): void
    {
        $propertyDefaultValues = [
            'string' => '',
            'bool' => false,
            'int' => 0,
            'array' => [],
            'object' => new stdClass(),
        ];

        $postTypeEntityProperties = (new ReflectionClass(PostTypeEntity::class))->getProperties();
        foreach ($postTypeEntityProperties as $property){
            $propertyType = (new \ReflectionProperty(PostTypeEntity::class, $property->getName()))->getType()->getName();
            $property = 'set' . ucfirst($property->name);
            $postType->$property($propertyDefaultValues[$propertyType]);
        }
    }
}