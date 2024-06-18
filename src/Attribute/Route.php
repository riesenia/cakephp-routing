<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Attribute;

abstract class Route
{
    protected string $name;
    protected string $scope = '/';
    protected ?string $plugin = null;

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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
