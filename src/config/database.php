<?php

return [
    'pgsql' => [
        'host' => $_ENV['POSTGRES_HOST'],
        'port' => $_ENV['POSTGRES_PORT'],
        'dbname' => $_ENV['POSTGRES_DB'],
        'user' => $_ENV['POSTGRES_USER'],
        'password' => $_ENV['POSTGRES_PASSWORD'],
        'charset' => 'utf8',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ]
];