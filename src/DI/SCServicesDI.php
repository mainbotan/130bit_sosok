<?php

namespace App\DI;

// Контракт ответа
use App\Contracts\BaseContract;

// Необходимые сервисы + апихи
use App\Services\SC\AuthoService as SCAuthoService;
use App\Services\SC\SearchService as SCSearchService;
use App\Api\SC\Search as SCSearchApi;

// Фабрика
use App\Factories\SCDTOFactory;

class SCServicesDI extends BaseContract
{   
    // Глобальные ошибки
    const CONF_ERROR = 'SoundCloud service configuration error';
    const UNKN_SERVICE_ERROR = 'Unknowm service name';
    
    // ID сервисов
    const SERVICE_SEARCH = 'search';

    private $router_response;
    private SCDTOFactory $sc_dto_factory;
    public function __construct() {
        $autho_service = new SCAuthoService();
        $this->router_response = $autho_service->getRouter();
        $this->sc_dto_factory = new SCDTOFactory();
    }

    // Точка входа
    public function build(string $service_name) {
        if ($this->router_response->code !== 200) {
            return $this->response(null, self::HTTP_ERROR, self::CONF_ERROR);
        }
        switch ($service_name) {
            case 'search':
                return $this->buildSearchService();
            default:
                return $this->response(null, self::HTTP_ERROR, self::UNKN_SERVICE_ERROR);
        }
    }

    // Сборка сервисов
    private function buildSearchService()
    {
        $api = new SCSearchApi($this->router_response->result);
        return $this->response(new SCSearchService($api, $this->sc_dto_factory));
    }
}
