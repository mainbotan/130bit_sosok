<?php

namespace App\UseCases\Composites\Album;

// Контракт
use App\Contracts\BaseContract;

// Трейт
use App\UseCases\Concerns\CompositeTrait;

class GetById extends BaseContract {
    use CompositeTrait;
    
    public function __construct(bool $storage_metric = false)
    {
        $this->initServices($storage_metric);
    }
    public function execute(string $id, array $options = [])
    {
        $this->metrics->start();

        // Проверяем холодный кэш
        $domain_service_request = $this->domain_di->build($this->domain_di::SERVICE_ALBUMS);
        if ($domain_service_request->code !== 200) {
            return $this->exit($domain_service_request, 'error'); // ошибка конфигурации
        }
        $service_response = $domain_service_request->result->getAlbumById($id);
        if ($service_response->code === 200) {
            return $this->exit($service_response);
        }

        // Подтягиваем со спотика
        $service_request = $this->spotify_di->build($this->domain_di::SERVICE_ALBUMS);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        $service_response = $service_request->result->getAlbumById($id);
        return $this->exit($service_response);
        
        // Сохраняем 
        // $service_response = $domain_service_request->result->createAlbum($domain_service_request->result);
        // return $this->exit($service_response);

    }
}
