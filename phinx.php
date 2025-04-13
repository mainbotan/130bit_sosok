<?php

require __DIR__ . '/vendor/autoload.php';

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Загружаем конфиг базы данных
$dbConfig = require __DIR__ . '/src/config/database.php';

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
            'host' => $dbConfig['pgsql']['host'],
            'name' => $dbConfig['pgsql']['dbname'],
            'user' => $dbConfig['pgsql']['user'],
            'pass' => $dbConfig['pgsql']['password'],
            'port' => $dbConfig['pgsql']['port'],
            'charset' => $dbConfig['pgsql']['charset']
        ],
    ],
];