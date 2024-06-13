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
}
