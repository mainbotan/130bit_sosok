<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\Services\Spotify\AuthoService as SpotifyAuthoService;
use App\DI\Spotify\ArtistsServiceDI as SpotifyArtistsDI;

$spotify_autho_service = new SpotifyAuthoService();
$spotify_autho_result = $spotify_autho_service->getRouter();

if ($spotify_autho_result->code === 200){
    $spotify_artists_service_DI = new SpotifyArtistsDI($spotify_autho_result->result);
    $spotify_artists_service = $spotify_artists_service_DI->get();
    $result = $spotify_artists_service->getArtistById(
        '5NMwoStnfHT4LdETlJSwDT', []
    );
}
var_dump($result);