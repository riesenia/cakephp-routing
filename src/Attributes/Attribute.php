<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Attributes;

abstract class Attribute
{
    protected string $name;

    public function __construct(
        protected string $scope = '/',
        protected ?string $plugin = null,
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}