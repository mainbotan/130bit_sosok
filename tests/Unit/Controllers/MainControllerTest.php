<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\Http\Controllers\MainController;
$controller = new MainController();

$result = $controller->execute(
    'artists.spotify.getByName',
    [
        'name' => 'Gunna'
    ]
);


var_dump($result);
