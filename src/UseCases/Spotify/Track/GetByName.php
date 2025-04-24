<?php

namespace App\UseCases\Spotify\Track;

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
            'type' => 'track', 'limit' => 1
        ]);
        if ($service_answer->code !== 200) {
            return $service_answer;
        }
        $track = $service_answer->result['tracks'][0];
        return parent::response($track);
    }
}
