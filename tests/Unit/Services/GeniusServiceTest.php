<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\Services\Genius\AuthoService as GeniusAuthoService;
use App\Services\Genius\ArtistsService as GeniusArtistsService;
use App\Services\Genius\SearchService as GeniusSearchService;
use App\Services\Genius\SongsService as GeniusSongsService;
use App\Api\Genius\Artists as GeniusArtistsApi;
use App\Api\Genius\Search as GeniusSearchApi;
use App\Api\Genius\Songs as GeniusSongsApi;

$genius_autho_service = new GeniusAuthoService();
$genius_autho_answer = $genius_autho_service->getRouter();

if ($genius_autho_answer->code === 200){

    // Тест сервиса артистов

    // $genius_artists_api = new GeniusArtistsApi($genius_autho_answer->result);
    // $genius_artists_service = new GeniusArtistsService($genius_artists_api);

    // $genius_artist_answer = $genius_artists_service->getArtistById('2197');
    // var_dump($genius_artist_answer);

    // $genius_artist_answer = $genius_artists_service->getArtistTracks('
    //     20185');
    // var_dump($genius_artist_answer);


    // Тест сервиса поиск

    // $genius_search_api = new GeniusSearchApi($genius_autho_answer->result);
    // $genius_search_service = new GeniusSearchService($genius_search_api);

    // $genius_search_answer = $genius_search_service->search (
    //     'knife talk'
    // );
    // var_dump($genius_search_answer);

    // Тест сервиса треков

    // $genius_songs_api = new GeniusSongsApi($genius_autho_answer->result);
    // $genius_songs_service = new GeniusSongsService($genius_songs_api);

    // $genius_song_answer = $genius_songs_service->getSongById(
    //     7165580, []
    // );
    // var_dump($genius_song_answer);

}
