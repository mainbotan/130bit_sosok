<?php

namespace App\UseCases\Domain\Artists;

// Контракт
use App\Contracts\BaseContract;
use App\DI\DomainServicesDI;

// Трейт
use App\UseCases\Concerns\DomainTrait;

class GetSeveral extends BaseContract {
    use DomainTrait;
    
    public function __construct(bool $storage_metric = false)
    {
        $this->initServices($storage_metric);
    }
    public function execute(array $array)
    {
        $this->metrics->start();

        $ids = isset($data['ids']) ? $data['ids'] : [];
        $options = isset($data['options']) ? $data['options'] : [];

        $service_request = $this->di->build($this->di::SERVICE_ARTISTS);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        return $this->exit($service_request->result->getSeveralArtists($ids, $options));
    }
}
