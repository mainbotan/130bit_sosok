<?php

namespace App\Services\Spotify;

use App\Services\BaseService as BaseService;
use App\Api\Spotify\Artists as SpotifyArtistsApi;
use App\DTO\ArtistCreateDTO as ArtistCreateDTO;

class ArtistsService extends BaseService{
    public function __construct(
        private SpotifyArtistsApi $spotify_artists
    ){}

    /**
     * Получение артиста по ID
     * @param string $id
     * @param array $options - массив опций
     * @return array
     */
    public function getArtistById(string $id, array $options=[]){
        // Подготавливаем перед запросов к апи
        $options['id'] = $id;
        try {
            $result = $this->spotify_artists->getArtist($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artist not found');
            }
            $artist = new ArtistCreateDTO($result);
            return parent::response($artist);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, 'Internal error'
            );
        }
    }
}