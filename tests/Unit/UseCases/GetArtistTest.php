<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\UseCases\Spotify\Album\GetById;
$use_case = new GetById(true);
$result = $use_case->execute('6t86ndvrKX2Vw5RXdkcxNr');

var_dump($result);
