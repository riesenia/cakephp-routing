<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Resource
{
    protected string $name;

    /**
     * @param string[]                                              $only
     * @param array{method?: string, path?: string,action?: string} $map
     */
    public function __construct(
        protected string $scope = '/',
        protected ?string $plugin = null,
        protected array $only = [],
        protected array $map = [],
        protected ?string $path = null
    ) {
        $this->initialize();
    }

    public function initialize(): void
    {
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getPlugin(): ?string
    {
        return $this->plugin;
    }

    /**
     * @return array{only: string[], map: array{method?: string, path?: string, action?: string}, path: string|null}
     */
    public function getOptions(): array
    {
        return ['only' => $this->only, 'map' => $this->map, 'path' => $this->path];
    }

    /**
     * @param array{only: string[], map: array{method?: string, path?: string, action?: string}, path: string|null} $options
     */
    public function setOptions(array $options): void
    {
        $this->only = $options['only'];
        $this->map = $options['map'];
        $this->path = $options['path'];
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
