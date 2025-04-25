<?php

namespace App\UseCases\Spotify\Album;

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
    public function execute(string $name, array $options = [])
    {
        $this->metrics->start();

        // Обращаемся к поисковику
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        $service = $service_request->result;
        $service_answer = $service->search($name, [
            'type' => 'album', 'limit' => 1
        ]);
        if ($service_answer->code !== 200) {
            return $this->exit($service_answer, 'error');
        }
        $album = $service_answer->result['albums'][0];
        return $this->exit(parent::response($album), 'status');
    }
}
