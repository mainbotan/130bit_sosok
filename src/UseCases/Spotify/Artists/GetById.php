<?php

namespace App\UseCases\Spotify\Artists;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

class GetById extends BaseContract {
    private SpotifyServicesDI $di;
    public function __construct()
    {
        $this->di = new SpotifyServicesDI();
    }
    public function getArtistById(string $id, array $options = [])
    {
        $service_result = $this->di->build($this->di::SERVICE_ARTISTS);
        if ($service_result->code !== 200) {
            return $service_result; // ошибка конфигурации
        }
        return $service_result->result->getArtistById($id);
    }
}
