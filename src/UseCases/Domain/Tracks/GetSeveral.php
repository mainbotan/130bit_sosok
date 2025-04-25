<?php

namespace App\UseCases\Domain\Tracks;

// Контракт
use App\Contracts\BaseContract;
use App\DI\DomainServicesDI;

class GetSeveral extends BaseContract {
    private DomainServicesDI $di;
    public function __construct()
    {
        $this->di = new DomainServicesDI();
    }
    public function execute(array $ids, array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_TRACKS);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        return $service_request->result->getSeveralTracks($ids, $options);
    }
}
