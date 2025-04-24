<?php

namespace App\UseCases\Spotify\Playlist;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

class GetTracks extends BaseContract {
    private SpotifyServicesDI $di;
    public function __construct()
    {
        $this->di = new SpotifyServicesDI();
    }
    public function execute(string $id, array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_PLAYLISTS);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        return $service_request->result->getPlaylistTracks($id, $options);
    }
}
