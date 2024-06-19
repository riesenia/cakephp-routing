<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

return [
    [
        'table' => 'items',
        'columns' => [
            'id' => ['type' => 'integer'],
            'name' => ['type' => 'string', 'default' => null],
            'sort' => ['type' => 'integer'],
            'quantity' => ['type' => 'integer', 'default' => 0],
        ],
        'constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ],
    [
        'table' => 'authors',
        'columns' => [
            'id' => ['type' => 'integer'],
            'name' => ['type' => 'string', 'default' => null],
        ],
        'constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ]
];
