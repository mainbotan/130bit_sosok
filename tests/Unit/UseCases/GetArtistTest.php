<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\UseCases\Spotify\Artists\GetById;
$use_case = new GetById();
$result = $use_case->getArtistById('');

var_dump($result);
