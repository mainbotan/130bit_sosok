<?php

namespace App\DI;

// Контракт ответа
use App\Contracts\BaseContract;

// Необходимые сервисы + апихи
use App\Services\Domain\AuthoService as AuthoService;
use App\Services\Domain\ArtistsService as ArtistsService;
use App\Services\Domain\AlbumsService as AlbumsService;
use App\Services\Domain\TracksService as TracksService;
use App\Services\Domain\PlaylistsService as PlaylistsService;
use App\Repositories\ArtistsRepository as ArtistsRepository;
use App\Repositories\AlbumsRepository as AlbumsRepository;
use App\Repositories\TracksRepository as TracksRepository;
use App\Repositories\PlaylistsRepository as PlaylistsRepository;

// Фабрика
use App\Factories\DomainDTOFactory;

class DomainServicesDI extends BaseContract
{   
    // Глобальные ошибки
    const CONF_ERROR = 'Domain service configuration error';
    const UNKN_SERVICE_ERROR = 'Unknowm service name';
    
    // ID сервисов
    const SERVICE_ARTISTS = 'artists';
    const SERVICE_ALBUMS = 'albums';
    const SERVICE_TRACKS = 'tracks';
    const SERVICE_PLAYLISTS = 'playlists';

    private $pdo_response;
    private DomainDTOFactory $domain_dto_factory;
    public function __construct() {
        $autho_service = new AuthoService();
        $this->pdo_response = $autho_service->getPDO();
        $this->domain_dto_factory = new DomainDTOFactory();
    }

    // Точка входа
    public function build(string $service_name) {
        if ($this->pdo_response->code !== 200) {
            return $this->response(null, self::HTTP_ERROR, self::CONF_ERROR);
        }
        switch ($service_name) {
            case 'artists':
                return $this->buildArtistsService();
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
        $repository = new ArtistsRepository($this->pdo_response->result);
        return parent::response(new ArtistsService($repository, $this->domain_dto_factory));
    }
    private function buildAlbumsService()
    {
        $repository = new AlbumsRepository($this->pdo_response->result);
        return parent::response(new AlbumsService($repository, $this->domain_dto_factory));
    }
    private function buildTracksService()
    {
        $repository = new TracksRepository($this->pdo_response->result);
        return $this->response(new TracksService($repository, $this->domain_dto_factory));
    }
    private function buildPlaylistsService()
    {
        $repository = new PlaylistsRepository($this->pdo_response->result);
        return $this->response(new PlaylistsService($repository, $this->domain_dto_factory));
    }

}
