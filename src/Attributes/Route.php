<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Route extends Attribute
{
    /** @param string[] $options */
    public function __construct(
        protected string $uri,
        protected ?string $action = null,
        protected ?array $options = [],
        protected string $scope = '/',
        protected ?string $plugin = null
    ) {
    }

    public function getUri(): string
    {
        return \strpos($this->uri, '/') === 0 ? $this->name . $this->uri : $this->name . '/' . $this->uri;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Allow setting action name in already initialized Route instance.
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return string[]
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }
}
