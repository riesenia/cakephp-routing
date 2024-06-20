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
 * AuthorsFixture.
 */
class AuthorsFixture extends TestFixture
{
    public string $table = 'authors';

    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'masa',
            ],
            [
                'id' => 2,
                'name' => 'segy',
            ]
        ];

        parent::init();
    }
}
