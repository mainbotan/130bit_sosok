<?php

namespace App\UseCases\Spotify\Album;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

// Трейт
use App\UseCases\Concerns\SpotifyTrait;

class GetById extends BaseContract {
    use SpotifyTrait;
    
    public function __construct(bool $storage_metric = false)
    {
        $this->initServices($storage_metric);
    }
    public function execute(string $id, array $options = [])
    {
        $this->metrics->start();

        $service_request = $this->di->build($this->di::SERVICE_ALBUMS);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // метрика с статусом error
        }

        $result = $service_request->result->getAlbumById($id, $options);
        return $this->exit($result, 'success'); // метрика с статусом success
    }
}
