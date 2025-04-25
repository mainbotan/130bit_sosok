<?php

namespace App\UseCases\Spotify\Artist;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

// Трейт
use App\UseCases\Concerns\SpotifyTrait;

class GetByName extends BaseContract {
    use SpotifyTrait;

    public function __construct(bool $storage_metric = false)
    {
        $this->initSpotifyServices($storage_metric);
    }
    public function execute(string $name, array $options = [])
    {
        $this->metrics->start();

        // Обращаемся к поисковику
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $this->exit($service_request); // ошибка конфигурации
        }
        $service = $service_request->result;
        $service_answer = $service->search($name, [
            'type' => 'artist', 'limit' => 1
        ]);
        if ($service_answer->code !== 200) {
            return $this->exit($service_answer);
        }
        $artist = $service_answer->result['artists'][0];
        return $this->exit(parent::response($artist));
    }
    private function exit(object $result_response) {
        return parent::response(
            $result_response->result,
            $result_response->code,
            $result_response->error,
            $this->metrics->end('success')
        );
    }
}
