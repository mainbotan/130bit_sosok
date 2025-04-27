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
        $this->initServices($storage_metric);
    }
    public function execute(array $data)
    {
        $this->metrics->start();

        $query = isset($data['query']) ? $data['query'] : '';
        $options = isset($data['options']) ? $data['options'] : [];
        
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        return $this->exit($service_request->result->search($query, $options), 'success');
    }
}
