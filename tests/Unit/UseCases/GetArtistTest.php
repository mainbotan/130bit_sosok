<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\UseCases\Composites\Album\GetById;
$use_case = new GetById(true); 
$result = $use_case->execute('5m0QGvbAbmhkBaoctKiVpp');

// use App\UseCases\Domain\Albums\GetCollection;
// $use_case = new GetCollection(true); 
// $result = $use_case->execute(['limit' => 20]);


var_dump($result);
