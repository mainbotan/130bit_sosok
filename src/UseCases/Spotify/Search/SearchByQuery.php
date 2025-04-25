<?php

namespace App\UseCases\Spotify\Search;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

// Трейт
use App\UseCases\Concerns\SpotifyTrait;

class SearchByQuery extends BaseContract {
    use SpotifyTrait;

    public function __construct(bool $storage_metric = false)
    {
        $this->initSpotifyServices($storage_metric);
    }
    public function execute(string $query, array $options = [])
    {
        $this->metrics->start();
        
        $service_result = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_result->code !== 200) {
            return $this->exit($service_result); // ошибка конфигурации
        }
        return $this->exit($service_result->result->search($query, $options));
    }
}
