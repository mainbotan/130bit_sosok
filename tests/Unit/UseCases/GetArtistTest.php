<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\UseCases\Genius\Artist\GetByName;
$use_case = new GetByName(true); 
$result = $use_case->execute('Big Baby Tape', ['deep' => true]);

var_dump($result);
