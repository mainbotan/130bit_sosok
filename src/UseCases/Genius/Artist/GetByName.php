<?php

namespace App\UseCases\Genius\Artist;

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

        // Ищем артиста
        $searched_tracks = $service_request->result;
        $best_artist = $this->findBestArtistMatch($searched_tracks, $query);
        if ($best_artist === null) {
            return $this->exit(parent::response(null, self::HTTP_NOT_FOUND, 'Artist from genius not found'), 'error');
        }
        
        if (isset($options['deep']) and $options['deep']) {
            // Запрашиваем подробности
            $service_request = $this->di->build($this->di::SERVICE_ARTISTS);
            if ($service_request->code !== 200) {
                return $this->exit($service_request, 'error'); // ошибка конфигурации
            }
            return $this->exit($service_request->result->getArtistById($best_artist->genius_id), 'success');
        }
        return $this->exit(parent::response($best_artist));
    }

    // Алгоритм поиска лучшего совпадения
    protected function findBestArtistMatch(array $tracks, string $query) {
        $query = strtolower(trim($query));
        $candidates = [];
        
        foreach ($tracks as $track) {
            foreach ($track->artists as $artist) {
                $artistName = strtolower($artist->name);
                $similarity = 0;
                
                // Точное совпадение
                if ($artistName === $query) {
                    return $artist;
                }
                
                // Частичное совпадение
                if (str_contains($artistName, $query)) {
                    $similarity = 50 + levenshtein($query, $artistName);
                    $candidates[$similarity] = $artist;
                }
            }
        }
        
        // Сортируем по "качеству" совпадения
        krsort($candidates);
        return reset($candidates) ?: null;
    }
}
