<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Resources extends Route
{
    /**
     * @param string[] $only
     * @param mixed[]  $actions
     * @param mixed[]  $map
     * @param mixed[]  $connectOptions
     */
    public function __construct(
        protected string $id = '',
        protected string $inflect = '',
        protected array $only = [],
        protected array $actions = [],
        protected array $map = [],
        protected string $prefix = '',
        protected array $connectOptions = [],
        protected string $path = '',
        protected string $scope = '/',
        protected string $plugin = ''
    ) {
        $this->initialize();
    }

    public function phpCode(): string
    {
        return '$builder->resources(' . $this->varExport($this->controller) . ', ' . $this->varExport($this->getOptions()) . ');';
    }

    /**
     * @return mixed[]
     */
    protected function getOptions(): array
    {
        return \array_filter([
            'id' => $this->id,
            'inflect' => $this->inflect,
            'only' => $this->only,
            'actions' => $this->actions,
            'map' => $this->map,
            'prefix' => $this->prefix,
            'connectOptions' => $this->connectOptions,
            'path' => $this->path
        ]);
    }
}
