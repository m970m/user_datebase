<?php

use App\Controller\UserController;

return [
    'GET' => [
        'list-users' => [UserController::class, 'listUsers'],
    ],
    'POST' => [
        'create-user' => [UserController::class, 'createUser'],
    ],
    'DELETE' => [
        'delete-user' => [UserController::class, 'deleteUser'],
    ]
];