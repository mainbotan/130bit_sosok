<?php

namespace App\DI;

// Контракт ответа
use App\Contracts\BaseContract;

// Необходимые сервисы + апихи
use App\Services\Spotify\AuthoService as SpotifyAuthoService;
use App\Services\Spotify\ArtistsService as SpotifyArtistsService;
use App\Services\Spotify\AlbumsService as SpotifyAlbumsService;
use App\Services\Spotify\TracksService as SpotifyTracksService;
use App\Services\Spotify\PlaylistsService as SpotifyPlaylistsService;
use App\Services\Spotify\SearchService as SpotifySearchService;
use App\Api\Spotify\Artists as SpotifyArtistsApi;
use App\Api\Spotify\Albums as SpotifyAlbumsApi;
use App\Api\Spotify\Tracks as SpotifyTracksApi;
use App\Api\Spotify\Playlists as SpotifyPlaylistsApi;
use App\Api\Spotify\Search as SpotifySearchApi;

// Фабрика
use App\Factories\SpotifyDTOFactory;

class SpotifyServicesDI extends BaseContract
{   
    // Глобальные ошибки
    const CONF_ERROR = 'Spotify service configuration error';
    const UNKN_SERVICE_ERROR = 'Unknowm service name';
    
    // ID сервисов
    const SERVICE_ARTISTS = 'artists';
    const SERVICE_ALBUMS = 'albums';
    const SERVICE_TRACKS = 'tracks';
    const SERVICE_PLAYLISTS = 'playlists';
    const SERVICE_SEARCH = 'search';

    private $router_response;
    private SpotifyDTOFactory $spotify_dto_factory;
    public function __construct() {
        $autho_service = new SpotifyAuthoService();
        $this->router_response = $autho_service->getRouter();
        $this->spotify_dto_factory = new SpotifyDTOFactory();
    }

    // Точка входа
    public function build(string $service_name) {
        if ($this->router_response->code !== 200) {
            return $this->response(null, self::HTTP_ERROR, self::CONF_ERROR);
        }
        switch ($service_name) {
            case 'artists':
                return $this->buildArtistsService();
            case 'search':
                return $this->buildSearchService();
            case 'albums':
                return $this->buildAlbumsService();
            case 'tracks':
                return $this->buildTracksService();
            case 'playlists':
                return $this->buildPlaylistsService();
            default:
                return $this->response(null, self::HTTP_ERROR, self::UNKN_SERVICE_ERROR);
        }
    }

    // Сборка сервисов
    private function buildArtistsService()
    {
        $api = new SpotifyArtistsApi($this->router_response->result);
        return parent::response(new SpotifyArtistsService($api, $this->spotify_dto_factory));
    }
    private function buildAlbumsService()
    {
        $api = new SpotifyAlbumsApi($this->router_response->result);
        return parent::response(new SpotifyAlbumsService($api, $this->spotify_dto_factory));
    }
    private function buildSearchService()
    {
        $api = new SpotifySearchApi($this->router_response->result);
        return $this->response(new SpotifySearchService($api, $this->spotify_dto_factory));
    }
    private function buildTracksService()
    {
        $api = new SpotifyTracksApi($this->router_response->result);
        return $this->response(new SpotifyTracksService($api, $this->spotify_dto_factory));
    }
    private function buildPlaylistsService()
    {
        $api = new SpotifyPlaylistsApi($this->router_response->result);
        return $this->response(new SpotifyPlaylistsService($api, $this->spotify_dto_factory));
    }

}
