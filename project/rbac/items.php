<?php
return [
    'user' => [
        'type' => 1,
        'description' => 'User',
        'ruleName' => 'userGroup',
    ],
    'moderator' => [
        'type' => 1,
        'description' => 'Moderator',
        'ruleName' => 'userGroup',
        'children' => [
            'user',
            'updateOwnPublication',
        ],
    ],
    'createPublication' => [
        'type' => 2,
        'description' => 'Create a publication',
    ],
    'updatePublication' => [
        'type' => 2,
        'description' => 'Update publication',
    ],
    'updateOwnPublication' => [
        'type' => 2,
        'description' => 'Update own publication',
        'ruleName' => 'isAuthor',
        'children' => [
            'updatePublication',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'ruleName' => 'userGroup',
        'children' => [
            'moderator',
            'updatePublication',
        ],
    ],
];
