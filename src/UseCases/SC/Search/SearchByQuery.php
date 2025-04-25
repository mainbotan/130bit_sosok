<?php

namespace App\UseCases\SC\Search;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SCServicesDI;

class SearchByQuery extends BaseContract {
    private SCServicesDI $di;
    public function __construct()
    {
        $this->di = new SCServicesDI();
    }
    public function execute(string $query, array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        return $service_request->result->search($query, $options);
    }
}
