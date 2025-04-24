<?php

namespace App\UseCases\Spotify\Artist;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

class GetById extends BaseContract {
    private SpotifyServicesDI $di;
    public function __construct()
    {
        $this->di = new SpotifyServicesDI();
    }
    public function execute(string $id, array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_ARTISTS);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        return $service_request->result->getArtistById($id);
    }
}
