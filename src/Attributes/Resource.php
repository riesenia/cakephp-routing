<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Resource extends Attribute
{
    /**
     * Regular expression for auto increment IDs.
     *
     * @var string
     */
    public const ID = '[0-9]+';

    /**
     * Regular expression for UUIDs.
     *
     * @var string
     */
    public const UUID = '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}';

    /**
     * @param string[]                                              $only
     * @param array{method?: string, path?: string,action?: string} $map
     * @param mixed[]                                               $connectOptions
     * @param mixed[]                                               $actions
     */
    public function __construct(
        protected array $only = [],
        protected array $map = [],
        protected ?string $path = null,
        protected array $connectOptions = [],
        protected string $inflect = 'dasherize',
        protected string $id = self::ID . '|' . self::UUID,
        protected array $actions = [],
        protected ?string $prefix = null,
        protected string $scope = '/',
        protected ?string $plugin = null
    ) {
        parent::__construct($scope, $plugin);
    }

    /**
     * @return array{only: string[], map: array{method?: string, path?: string, action?: string}, path: string|null}
     */
    public function getOptions(): array
    {
        return ['only' => $this->only, 'map' => $this->map, 'path' => $this->path];
    }
}
