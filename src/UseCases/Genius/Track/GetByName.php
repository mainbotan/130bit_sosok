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
        $this->initServices($storage_metric);
    }
    public function execute(array $data)
    {
        $this->metrics->start();

        $name = isset($data['name']) ? $data['name'] : '';
        $options = isset($data['options']) ? $data['options'] : [];

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
