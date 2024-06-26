<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Connect extends Route
{
    protected string $action;

    /** @param mixed[] $options */
    public function __construct(
        protected string $uri = '',
        protected array $options = [],
        protected string $scope = '/',
        protected ?string $plugin = null
    ) {
        $this->initialize();
    }

    public function phpCode(): string
    {
        return '$builder->connect(' . $this->varExport($this->getUri()) . ', ' . $this->varExport(['controller' => $this->controller, 'action' => $this->action]) . ', ' . $this->varExport($this->getOptions()) . ');';
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    protected function getUri(): string
    {
        return (\str_starts_with($this->uri, '/') ? '' : '/' . \strtolower($this->controller) . '/') . ($this->uri ?: $this->dashedAction());
    }

    protected function dashedAction(): string
    {
        return \preg_replace_callback('/[A-Z]/', fn ($matches) => '-' . \strtolower($matches[0]), $this->action) ?? throw new \Exception('preg_replace_callback failed');
    }

    /**
     * @return mixed[]
     */
    protected function getOptions(): array
    {
        return $this->options;
    }
}
