<?php

namespace App\UseCases\Genius\Track;

// Контракт
use App\Contracts\BaseContract;
use App\DI\GeniusServicesDI;

// Трейт
use App\UseCases\Concerns\GeniusTrait;

class GetById extends BaseContract {
    use GeniusTrait;
    
    public function __construct(bool $storage_metric = false)
    {
        $this->initServices($storage_metric);
    }
    public function execute(string $id, array $options = [])
    {
        $this->metrics->start();

        $service_request = $this->di->build($this->di::SERVICE_TRACKS);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        return $this->exit($service_request->result->getSongById($id), 'success');
    }
}
