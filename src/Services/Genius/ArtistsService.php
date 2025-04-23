<?php

namespace App\Services\Genius;

use App\Services\BaseService as BaseService;
use App\Api\Genius\Artists as GeniusArtistsApi;
use App\Factories\GeniusDTOFactory;

class ArtistsService extends BaseService{
    private GeniusDTOFactory $genius_dto_factory;
    public function __construct(
        private GeniusArtistsApi $genius_artists
    ) { 
        $this->genius_dto_factory = new GeniusDTOFactory();
    }

    /**
     * Получение артиста по ID
     * @param string $id
     * @param array $options - массив опций
     * @return object
     */
    public function getArtistById (
        string $id, array $options=[]
    ){
        $text_format = $options['text_format'] ?? 'plain';
        try {
            $result = $this->genius_artists->getArtist($id, $text_format);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artist not found');
            }
            $artist = $this->genius_dto_factory::artist($result, false);
            return parent::response($artist);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }

    /**
     * Получение треков артиста
     * @param string $id
     * @param array $options - массив опций
     * @return object
     */
    public function getArtistTracks (
        string $id, array $options=[]
    ){
        try {
            $result = $this->genius_artists->getArtistTracks($id, $options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artist not found');
            }
            $tracks = $this->genius_dto_factory::tracks($result, false);
            return parent::response($tracks);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }
}