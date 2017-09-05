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
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'ruleName' => 'userGroup',
        'children' => [
            'moderator',
        ],
    ],
];
