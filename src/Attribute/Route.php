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
    protected string $controller;
    protected string $scope = '/';
    protected string $plugin = '';

    public function initialize(): void
    {
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getPlugin(): string
    {
        return $this->plugin;
    }

    abstract public function phpCode(): string;

    protected function varExport(mixed $var): string
    {
        if (!\is_array($var)) {
            return \var_export($var, true);
        }

        $indexed = \array_keys($var) === \range(0, \count($var) - 1);

        $formatted = [];

        foreach ($var as $key => $value) {
            $formatted[] = ($indexed ? '' : \var_export($key, true) . ' => ') . $this->varExport($value);
        }

        return '[' . \implode(', ', $formatted) . ']';
    }
}
