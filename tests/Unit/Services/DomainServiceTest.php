<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\Api\Spotify\Playlists;
use App\Api\Spotify\Tracks;
use App\Services\Domain\AuthoService as AuthoService;
use App\Services\Domain\ArtistsService as ArtistsService;
use App\Services\Domain\AlbumsService as AlbumsService;
use App\Services\Domain\TracksService as TracksService;
use App\Services\Domain\PlaylistsService as PlaylistsService;
use App\Repositories\ArtistsRepository as ArtistsRepository;
use App\Repositories\AlbumsRepository as AlbumsRepository;
use App\Repositories\TracksRepository as TracksRepository;
use App\Repositories\PlaylistsRepository as PlaylistsRepository;

// Инициализация
$autho_service = new AuthoService();
$autho_service_answer = $autho_service->getPDO();

if ($autho_service_answer->code === 200) {

    $pdo = $autho_service_answer->result;

    // Тест сервиса артистов
    
    // $artists_repository = new ArtistsRepository($pdo);
    // $artists_service = new ArtistsService($artists_repository);
    
    // $artists_service_answer = $artists_service->getAllArtists([
    //     'limit' => 20, 'offset' => 0
    // ]);
    // var_dump($artists_service_answer);

    // $artists_service_answer = $artists_service->getArtistById(
    //     '699OTQXzgjhIYAHMy9RyPD'
    // );
    // var_dump($artists_service_answer);

    // $artists_service_answer = $artists_service->searchArtistsByName(
    //     'Play'
    // );
    // var_dump($artists_service_answer);


    // Тест сервиса альбомов
    
    // $albums_repository = new AlbumsRepository($pdo);
    // $albums_service = new AlbumsService($albums_repository);

    // $albums_service_answer = $albums_service->getAllAlbums(
    //     ['limit' => 20, 'offset' => 0]
    // );
    // var_dump($albums_service_answer);

    // Тест сервиса треков
    
    // $tracks_repository = new TracksRepository($pdo);
    // $tracks_service = new TracksService($tracks_repository);

    // $tracks_service_answer = $tracks_service->getAllTracks([
    //     'limit' => 20, 'offset' => 0
    // ]);

    // var_dump($tracks_service_answer);


    // Тест сервиса плейлистов
    
    // $playlists_repository = new PlaylistsRepository($pdo);
    // $playlists_service = new PlaylistsService($playlists_repository);

    // $playlists_service_answer = $playlists_service->getAllPlaylists([
    //     'limit' => 20, 'offset' => 0
    // ]);

    // var_dump($playlists_service_answer);

}