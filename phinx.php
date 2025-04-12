<?php

require __DIR__ . '/vendor/autoload.php';

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinx_migrations',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'pgsql',
            'host' => getenv('POSTGRES_HOST'),
            'name' => getenv('POSTGRES_DB'),
            'user' => getenv('POSTGRES_USER'),
            'pass' => getenv('POSTGRES_PASSWORD'),
            'port' => getenv('POSTGRES_PORT'),
            'charset' => 'utf8',
        ],
    ],
];