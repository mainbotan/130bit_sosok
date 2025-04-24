<?php

namespace App\UseCases\Spotify\Search;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

class SearchByQuery extends BaseContract {
    private SpotifyServicesDI $di;
    public function __construct()
    {
        $this->di = new SpotifyServicesDI();
    }
    public function execute(string $query, array $options = [])
    {
        $service_result = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_result->code !== 200) {
            return $service_result; // ошибка конфигурации
        }
        return $service_result->result->search($query, $options);
    }
}
