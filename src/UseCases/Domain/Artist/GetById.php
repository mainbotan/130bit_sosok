<?php

namespace App\UseCases\Domain\Artist;

// Контракт
use App\Contracts\BaseContract;
use App\DI\DomainServicesDI;

// Трейт
use App\UseCases\Concerns\DomainTrait;

class GetById extends BaseContract {
    use DomainTrait;
    
    public function __construct(bool $storage_metric = false)
    {
        $this->initServices($storage_metric);
    }
    public function execute(array $data)
    {
        $this->metrics->start();

        $id = isset($data['id']) ? $data['id'] : null;
        $options = isset($data['options']) ? $data['options'] : [];

        $service_request = $this->di->build($this->di::SERVICE_ARTISTS);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        return $this->exit($service_request->result->getArtistById($id));
    }
}
