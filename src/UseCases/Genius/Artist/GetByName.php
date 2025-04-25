<?php

namespace App\UseCases\Genius\Artist;

// Контракт
use App\Contracts\BaseContract;
use App\DI\GeniusServicesDI;

class GetByName extends BaseContract {
    private GeniusServicesDI $di;
    public function __construct()
    {
        $this->di = new GeniusServicesDI();
    }
    public function execute(string $query, array $options = [])
    {
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $service_request; // ошибка конфигурации
        }
        $service_request = $service_request->result->search($query);
        if ($service_request->code !== 200) {
            return $service_request;
        }

        $searched_tracks = $service_request->result;
        $best_artist = $this->findBestArtistMatch($searched_tracks, $query);
        if ($best_artist === null) {
            return parent::response(null, self::HTTP_NOT_FOUND, 'Artist from genius not found');
        }
        return parent::response($best_artist);
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
