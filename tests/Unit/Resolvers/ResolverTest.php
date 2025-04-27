<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\Resolvers\ArtistsResolver;
$resolver = new ArtistsResolver();

$result = $resolver->execute(
    'genius.getByName',
    [
        'name' => 'gunna',
        'options' => [
            'deep' => true
        ]
    ]
);


var_dump($result);
