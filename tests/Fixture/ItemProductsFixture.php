<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ItemProductsFixture.
 */
class ItemProductsFixture extends TestFixture
{
    public string $table = 'item_products';

    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'item_id' => 1,
                'name' => 'product 1',
                'price' => 20
            ],
            [
                'id' => 2,
                'item_id' => 1,
                'name' => 'product 2',
                'price' => 30
            ]
        ];

        parent::init();
    }
}
