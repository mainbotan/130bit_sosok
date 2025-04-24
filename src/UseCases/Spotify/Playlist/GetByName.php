<?php

namespace App\UseCases\Spotify\Playlist;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

class GetByName extends BaseContract {
    private SpotifyServicesDI $di;
    public function __construct()
    {
        $this->di = new SpotifyServicesDI();
    }
    public function execute(string $name, array $options = [])
    {
        // Обращаемся к поисковику
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        $service = $service_request->result;
        $service_answer = $service->search($name, [
            'type' => 'playlist', 'limit' => 1
        ]);
        if ($service_answer->code !== 200) {
            return $service_answer;
        }
        $playlist = $service_answer->result['playlists'][0];
        return parent::response($playlist);
    }
}
