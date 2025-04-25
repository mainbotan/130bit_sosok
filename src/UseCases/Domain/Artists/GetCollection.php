<?php

namespace App\UseCases\Domain\Artists;

// Контракт
use App\Contracts\BaseContract;
use App\DI\DomainServicesDI;

class GetCollection extends BaseContract {
    private DomainServicesDI $di;
    public function __construct()
    {
        $this->di = new DomainServicesDI();
    }
    public function execute(array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_ARTISTS);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        return $service_request->result->getAllArtists($options);
    }
}
