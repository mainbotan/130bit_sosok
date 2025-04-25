<?php

namespace App\UseCases\Domain\Album;

// Контракт
use App\Contracts\BaseContract;
use App\DI\DomainServicesDI;

class GetByName extends BaseContract {
    private DomainServicesDI $di;
    public function __construct()
    {
        $this->di = new DomainServicesDI();
    }
    public function execute(string $name, array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_ALBUMS);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        return $service_request->result->getAlbumByName($name);
    }
}
