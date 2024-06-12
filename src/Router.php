<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing;

use Cake\Core\Configure;
use Riesenia\Routing\Attributes\Resource;

class Router
{
    /**
     * @var array<string,array<\Riesenia\Routing\Attributes\Resource>>
     */
    protected array $resources = [];

    public function addRoute(Resource $route): void
    {
        $this->resources[$route->getScope()][] = $route;
    }

    /**
     * Build routes to the controllers.
     *
     * @throws \ReflectionException
     *
     * @return array<string,array<\Riesenia\Routing\Attributes\Resource>>
     */
    public function getRoutes(): array
    {
        foreach ($this->getArray(Configure::read('Controllers')) as $controller) {
            if (!\is_string($controller) || !\class_exists($controller)) {
                continue;
            }

            $reflectedClass = new \ReflectionClass($controller);

            foreach ($reflectedClass->getAttributes() as $attribute) {
                $instance = $attribute->newInstance();

                if (!$instance instanceof Resource) {
                    continue;
                }

                $instance->setName(\substr($reflectedClass->getShortName(), 0, -10));
                $this->addRoute($instance);
            }
        }

        return $this->resources;
    }

    /**
     * @return array<string,string>
     */
    private function getArray(mixed $value): array
    {
        return \is_array($value) ? (array) $value : throw new \InvalidArgumentException('Value is not a string!');
    }
}
