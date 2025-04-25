<?php

namespace App\UseCases\Domain\Albums;

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
        $service_request = $this->di->build($this->di::SERVICE_ALBUMS);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        return $service_request->result->getAllAlbums($options);
    }
}
