<?php

namespace App\UseCases\Genius\Track;

// Контракт
use App\Contracts\BaseContract;
use App\DI\GeniusServicesDI;

class GetByName extends BaseContract {
    private GeniusServicesDI $di;
    public function __construct()
    {
        $this->di = new GeniusServicesDI();
    }
    public function execute(string $query, array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        $service_request = $service_request->result->search($query);
        if ($service_request->code !== 200) {
            return $service_request;
        }
        $searched_tracks = $service_request->result;
        return parent::response($searched_tracks[0]);
    }
}
