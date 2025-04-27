<?php

namespace App\UseCases\Domain\Album;

// Контракт
use App\Contracts\BaseContract;
use App\DI\DomainServicesDI;

// Трейт
use App\UseCases\Concerns\DomainTrait;

class Save extends BaseContract {
    use DomainTrait;
    
    public function __construct(bool $storage_metric = false)
    {
        $this->initServices($storage_metric);
    }
    public function execute(object $dto)
    {
        $this->metrics->start();

        $service_request = $this->di->build($this->di::SERVICE_ALBUMS);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        $service_response = $service_request->result->createAlbum($dto);
        if ($service_response->code !== 200) {
            return $this->exit($service_response, 'error');
        }
        return $this->exit($service_response);
    }
}
