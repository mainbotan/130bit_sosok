<?php

namespace App\DI;

// Контракт ответа
use App\Contracts\BaseContract;

// Необходимые сервисы + апихи
use App\Services\Genius\AuthoService as GeniusAuthoService;
use App\Services\Genius\ArtistsService as GeniusArtistsService;
use App\Services\Genius\SearchService as GeniusSearchService;
use App\Services\Genius\SongsService as GeniusSongsService;
use App\Api\Genius\Artists as GeniusArtistsApi;
use App\Api\Genius\Search as GeniusSearchApi;
use App\Api\Genius\Songs as GeniusSongsApi;

// Фабрика
use App\Factories\GeniusDTOFactory;

class GeniusServicesDI extends BaseContract
{   
    // Глобальные ошибки
    const CONF_ERROR = 'Genius service configuration error';
    const UNKN_SERVICE_ERROR = 'Unknowm service name';
    
    // ID сервисов
    const SERVICE_ARTISTS = 'artists';
    const SERVICE_TRACKS = 'tracks';
    const SERVICE_SEARCH = 'search';

    private $router_response;
    private GeniusDTOFactory $genius_dto_factory;
    public function __construct() {
        $autho_service = new GeniusAuthoService();
        $this->router_response = $autho_service->getRouter();
        $this->genius_dto_factory = new GeniusDTOFactory();
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
            case 'tracks':
                return $this->buildTracksService();
            default:
                return $this->response(null, self::HTTP_ERROR, self::UNKN_SERVICE_ERROR);
        }
    }

    // Сборка сервисов
    private function buildArtistsService()
    {
        $api = new GeniusArtistsApi($this->router_response->result);
        return parent::response(new GeniusArtistsService($api, $this->genius_dto_factory));
    }
    private function buildSearchService()
    {
        $api = new GeniusSearchApi($this->router_response->result);
        return $this->response(new GeniusSearchService($api, $this->genius_dto_factory));
    }
    private function buildTracksService()
    {
        $api = new GeniusSongsApi($this->router_response->result);
        return $this->response(new GeniusSongsService($api, $this->genius_dto_factory));
    }
}
