<?php

namespace App\UseCases\Composites\Album;

// Контракт
use App\Contracts\BaseContract;

// Трейт
use App\UseCases\Concerns\CompositeTrait;

// Тянем нужные юзкейсы
use App\UseCases\Domain\Album\Save as DomainSave;
use App\UseCases\Domain\Album\GetById as DomainGetById;
use App\UseCases\Spotify\Album\GetById as SpotifyGetById;

class GetById extends BaseContract {
    use CompositeTrait;

    private SpotifyGetById $spotify_get_by_id;
    private DomainGetById $domain_get_by_id;
    private DomainSave $domain_save;
    public function __construct(bool $storage_metric = false)
    {
        $this->initServices($storage_metric);
        
        // Отключаем метрики памями для дочерних юз кейсов
        $this->spotify_get_by_id = new SpotifyGetById(false);
        $this->domain_get_by_id = new DomainGetById(false);
        $this->domain_save = new DomainSave(false);
    }
    public function execute(array $data)
    {
        $this->metrics->start();

        $id = isset($data['id']) ? $data['id'] : null;
        $options = isset($data['options']) ? $data['options'] : [];

        // Чекаем холодный кэш
        $domain_request = $this->domain_get_by_id->execute(['id' => $id]);
        if ($domain_request->code === 200) { return $this->exit($domain_request); }
        
        // Чекаем спотик
        $spotify_request = $this->spotify_get_by_id->execute(['id' => $id]);
        if ($spotify_request->code !== 200){ return $this->exit($spotify_request, 'error'); }

        // Сохраняем
        $domain_request = $this->domain_save->execute($spotify_request->result);
        if ($domain_request->code !== 200) { return $this->exit($domain_request, 'error'); }
        return $this->exit($domain_request);
    }
}
