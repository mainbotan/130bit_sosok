<?php

namespace App\UseCases\Genius\Track;

// Контракт
use App\Contracts\BaseContract;
use App\DI\GeniusServicesDI;

class GetById extends BaseContract {
    private GeniusServicesDI $di;
    public function __construct()
    {
        $this->di = new GeniusServicesDI();
    }
    public function execute(string $id, array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_TRACKS);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        return $service_request->result->getSongById($id);
    }
}
