<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\Services\Spotify\AuthoService as SpotifyAuthoService;
use App\Services\Spotify\ArtistsService as SpotifyArtistsService;
use App\Api\Spotify\Artists as SpotifyArtistsApi;

$spotify_autho_service = new SpotifyAuthoService();
$spotify_autho_answer = $spotify_autho_service->getRouter();

if ($spotify_autho_answer->code === 200){

    // Тест сервиса артистов

    // $spotify_artists_api = new SpotifyArtistsApi($spotify_autho_answer->result);
    // $spotify_artists_service = new SpotifyArtistsService($spotify_artists_api);

    // $spotify_artist_answer = $spotify_artists_service->getArtistById(
    //     '6Ip8FS7vWT1uKkJSweANQK', []
    // );
    // var_dump($spotify_artist_answer);

    // $spotify_artist_top_tracks_answer = $spotify_artists_service->getArtistTopTracks(
    //     '5H4yInM5zmHqpKIoMNAx4r'
    // );
    // var_dump($spotify_artist_top_tracks_answer);

    // $spotify_several_artists_answer = $spotify_artists_service->getSeveralArtists(
    //     ['5H4yInM5zmHqpKIoMNAx4r', '6Ip8FS7vWT1uKkJSweANQK']
    // );
    // var_dump($spotify_several_artists_answer);

    // $spotify_artist_albums_answer = $spotify_artists_service->getArtistAlbums (
    //     '5H4yInM5zmHqpKIoMNAx4r', [
    //         'limit' => 10,
    //         'offset' => 0
    //     ]
    // );
    // var_dump($spotify_artist_albums_answer);
}