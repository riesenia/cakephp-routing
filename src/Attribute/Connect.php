<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Attribute;

use Cake\Utility\Inflector;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Connect extends Route
{
    protected string $action;

    /**
     * @param mixed[] $defaults
     * @param mixed[] $options
     */
    public function __construct(
        protected string $uri = '',
        protected array $defaults = [],
        protected array $options = [],
        protected string $scope = '/',
        protected string $plugin = ''
    ) {
        $this->initialize();
    }

    public function phpCode(): string
    {
        return '$builder->connect(' . $this->varExport($this->getUri()) . ', ' . $this->varExport($this->getDefaults()) . ', ' . $this->varExport($this->getOptions()) . ');';
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    protected function getUri(): string
    {
        return (\str_starts_with($this->uri, '/') ? '' : '/' . Inflector::dasherize($this->controller) . '/') . ($this->uri ?: Inflector::dasherize($this->action));
    }

    /**
     * @return mixed[]
     */
    protected function getDefaults(): array
    {
        return \array_merge(['controller' => $this->controller, 'action' => $this->action], $this->defaults);
    }

    /**
     * @return mixed[]
     */
    protected function getOptions(): array
    {
        return $this->options;
    }
}
