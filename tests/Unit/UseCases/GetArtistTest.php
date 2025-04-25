<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\UseCases\Genius\Artist\GetTracks;
$use_case = new GetTracks();
$result = $use_case->execute('2197');

var_dump($result);
