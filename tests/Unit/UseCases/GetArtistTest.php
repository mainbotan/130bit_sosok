<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\UseCases\Spotify\Album\GetByName;
$use_case = new GetByName(true); 
$result = $use_case->execute(['name' => 'In the Air']);

// use App\UseCases\Domain\Albums\GetCollection;
// $use_case = new GetCollection(true); 
// $result = $use_case->execute(['limit' => 20]);


var_dump($result);
