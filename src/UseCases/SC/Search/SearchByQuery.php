<?php

namespace App\UseCases\SC\Search;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SCServicesDI;

// Трейт
use App\UseCases\Concerns\SCTrait;

class SearchByQuery extends BaseContract {
    use SCTrait;
    
    public function __construct(bool $storage_metric = false)
    {
        $this->initSpotifyServices($storage_metric);
    }
    public function execute(string $query, array $options = [])
    {
        $this->metrics->start();
        
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        return $this->exit($service_request->result->search($query, $options), 'success');
    }
}
