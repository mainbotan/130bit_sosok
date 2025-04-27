<?php

namespace App\UseCases\Spotify\Playlist;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

// Трейт
use App\UseCases\Concerns\SpotifyTrait;

class GetByName extends BaseContract {
    use SpotifyTrait;

    public function __construct(bool $storage_metric = false)
    {
        $this->initServices($storage_metric);
    }
    public function execute(array $data)
    {
        $this->metrics->start();

        $name = isset($data['name']) ? $data['name'] : null;
        $options = isset($data['options']) ? $data['options'] : [];

        // Обращаемся к поисковику
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        $service = $service_request->result;
        $service_answer = $service->search($name, [
            'type' => 'playlist', 'limit' => 1
        ]);
        if ($service_answer->code !== 200) {
            return $this->exit($service_answer, 'error');
        }
        $playlist = $service_answer->result['playlists'][0];
        return $this->exit(parent::response($playlist), 'success');
    }
}
