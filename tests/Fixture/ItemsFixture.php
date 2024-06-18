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
 * ItemsFixture.
 */
class ItemsFixture extends TestFixture
{
    public string $table = 'items';

    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'item 1',
                'sort' => 1,
                'quantity' => 10,
            ],
            [
                'id' => 2,
                'name' => 'item 2',
                'sort' => 2,
                'quantity' => 100,
            ],
            [
                'id' => 3,
                'name' => 'item 3',
                'sort' => 3,
                'quantity' => 10
            ],
            [
                'id' => 4,
                'name' => 'item 4',
                'sort' => 4,
                'quantity' => 5
            ],
            [
                'id' => 5,
                'name' => 'item 5',
                'sort' => 5,
                'quantity' => 4
            ]
        ];

        parent::init();
    }
}
