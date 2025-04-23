<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\Services\Spotify\AuthoService as SpotifyAuthoService;
use App\Services\Spotify\ArtistsService as SpotifyArtistsService;
use App\Services\Spotify\AlbumsService as SpotifyAlbumsService;
use App\Services\Spotify\TracksService as SpotifyTracksService;
use App\Services\Spotify\PlaylistsService as SpotifyPlaylistsService;
use App\Api\Spotify\Artists as SpotifyArtistsApi;
use App\Api\Spotify\Albums as SpotifyAlbumsApi;
use App\Api\Spotify\Tracks as SpotifyTracksApi;
use App\Api\Spotify\Playlists as SpotifyPlaylistsApi;

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


    // Тест сервиса альбомов

    // $spotify_albums_api = new SpotifyAlbumsApi($spotify_autho_answer->result);
    // $spotify_albums_service = new SpotifyAlbumsService($spotify_albums_api);

    // $spotify_album_answer = $spotify_albums_service->getAlbumById(
    //     '1pnm9zBlblhTRlE46ItLzU'
    // );
    // var_dump($spotify_album_answer);

    // $spotify_album_tracks_answer = $spotify_albums_service->getAlbumTracks(
    //     '1pnm9zBlblhTRlE46ItLzU',
    // );
    // var_dump($spotify_album_tracks_answer);

    // $spotify_several_albums_answer = $spotify_albums_service->getSeveralAlbums(
    //     ['1pnm9zBlblhTRlE46ItLzU', '5ZewPP4nTMtJTn4krePB2d']
    // );
    // var_dump($spotify_several_albums_answer);



    // Тест сервиса треков

    // $spotify_tracks_api = new SpotifyTracksApi($spotify_autho_answer->result);
    // $spotify_tracks_service = new SpotifyTracksService($spotify_tracks_api);

    // $spotify_track_answer = $spotify_tracks_service->getTrackById(
    //     '7lBwQzYymGLZz9ybb4isD0'
    // );
    // var_dump($spotify_track_answer);

    // $spotify_tracks_answer = $spotify_tracks_service->getSeveralTracks(
    //     ['7sVbKoBdhXtYCEOO6qC1SN', '1Aw7MLqN5oqvIva5KWDpGl']
    // );
    // var_dump($spotify_tracks_answer);


    // Тест сервиса плейлистов

    $spotify_playlists_api = new SpotifyPlaylistsApi($spotify_autho_answer->result);
    $spotify_playlists_service = new SpotifyPlaylistsService($spotify_playlists_api);

    // $spotify_playlist_answer = $spotify_playlists_service->getPlaylistById(
    //     '50iY6munHRsEHFeMlQraVz'
    // );
    // var_dump($spotify_playlist_answer);

    // $spotify_playlist_tracks_answer = $spotify_playlists_service->getPlaylistTracks(
    //     '50iY6munHRsEHFeMlQraVz'
    // );
    // var_dump($spotify_playlist_tracks_answer);
}
