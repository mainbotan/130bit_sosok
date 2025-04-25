<?php

namespace App\UseCases\Genius\Track;

// Контракт
use App\Contracts\BaseContract;
use App\DI\GeniusServicesDI;

// Трейт
use App\UseCases\Concerns\GeniusTrait;

class GetByName extends BaseContract {
    use GeniusTrait;
    
    public function __construct(bool $storage_metric = false)
    {
        $this->initSpotifyServices($storage_metric);
    }
    public function execute(string $query, array $options = [])
    {
        $this->metrics->start();

        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error'); // ошибка конфигурации
        }
        $service_request = $service_request->result->search($query);
        if ($service_request->code !== 200) {
            return $this->exit($service_request, 'error');
        }
        $searched_tracks = $service_request->result;

        if (isset($options['deep']) and $options['deep']) {
            $service_request = $this->di->build($this->di::SERVICE_TRACKS);
            if ($service_request->code !== 200) {
                return $this->exit($service_request, 'error'); // ошибка конфигурации
            }
            return $this->exit($service_request->result->getSongById($searched_tracks[0]->genius_id));
        }
        return $this->exit(parent::response($searched_tracks[0]), 'success');
    }
}
