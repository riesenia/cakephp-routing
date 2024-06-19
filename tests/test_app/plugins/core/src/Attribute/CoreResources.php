<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Core\Attribute;

use Riesenia\Routing\Attribute\Resources;

#[\Attribute(\Attribute::TARGET_CLASS)]
class CoreResources extends Resources
{
    public function initialize(): void
    {
        $this->scope = '/api';
        $this->plugin = 'Riesenia/Core';
    }
}
