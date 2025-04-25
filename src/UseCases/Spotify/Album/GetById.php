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
        $this->initSpotifyServices($storage_metric);
    }
    public function execute(string $id, array $options = [])
    {
        $this->metrics->start();

        // Обращаемся к поисковику
        $service_request = $this->di->build($this->di::SERVICE_ALBUMS);
        if ($service_request->code !== 200) {
            return $this->exit($service_request); // ошибка конфигурации
        }
        return $this->exit($service_request->result->getAlbumById($id, $options));
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
