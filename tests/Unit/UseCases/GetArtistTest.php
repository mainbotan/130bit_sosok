<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\UseCases\Spotify\Playlist\GetTracks;
$use_case = new GetTracks();
$result = $use_case->execute('5JcLzm5HVkwb5l4Cea2zcK');

var_dump($result);
