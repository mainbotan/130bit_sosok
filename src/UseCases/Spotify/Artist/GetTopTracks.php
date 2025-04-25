<?php

namespace App\UseCases\Spotify\Artist;

// Контракт
use App\Contracts\BaseContract;
use App\DI\SpotifyServicesDI;

// Трейт
use App\UseCases\Concerns\SpotifyTrait;

class GetTopTracks extends BaseContract {
    use SpotifyTrait;

    public function __construct(bool $storage_metric = false)
    {
        $this->initSpotifyServices($storage_metric);
    }
    public function execute(string $id, array $options = [])
    {
        $this->metrics->start();

        $service_request = $this->di->build($this->di::SERVICE_ARTISTS);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        return $this->exit($service_request->result->getArtistTopTracks(
            $id, $options
        ), 'success');
    }
}
