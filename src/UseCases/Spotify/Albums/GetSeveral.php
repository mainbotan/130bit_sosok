<?php

namespace App\UseCases\Spotify\Albums;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

class GetSeveral extends BaseContract {
    private SpotifyServicesDI $di;
    public function __construct()
    {
        $this->di = new SpotifyServicesDI();
    }
    public function execute(array $ids, array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_ALBUMS);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        return $service_request->result->getSeveralAlbums($ids);
    }
}
