<?php

namespace App\Services\Spotify;

use App\Services\BaseService as BaseService;
use App\Api\Spotify\Artists as SpotifyArtistsApi;
use App\Factories\SpotifyDTOFactory;

class ArtistsService extends BaseService{
    private SpotifyDTOFactory $spotify_dto_factory;
    public function __construct(
        private SpotifyArtistsApi $spotify_artists
    ) { 
        $this->spotify_dto_factory = new SpotifyDTOFactory();
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
        // Подготавливаем перед запросов к апи
        $options['id'] = $id;
        try {
            $result = $this->spotify_artists->getArtist($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artist not found');
            }
            $artist = $this->spotify_dto_factory::artist($result, false);
            return parent::response($artist);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }
    
    /**
     * Получение топа треков
     * @param string $id - id артиста
     * @param array $options - массив опций
     * @return object
     */
    public function getArtistTopTracks (
        string $id, array $options=[]
    ){
        // Подготавливаем перед запросов к апи
        $options['id'] = $id;
        try {
            $result = $this->spotify_artists->getTopTracks($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Top tracks not found');
            }               
            $tracks = $this->spotify_dto_factory::tracks($result['tracks'], false);
            return parent::response($tracks);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }


    /**
     * Получение нескольких альбомов
     * @param array $ids 
     * @return object
     */
    public function getSeveralArtists (
        array $ids
    ){
        // Подготавливаем перед запросов к апи
        $options['ids'] = $ids;
        try {
            $result = $this->spotify_artists->getSeveralArtists($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artists not found');
            }               
            $artists = $this->spotify_dto_factory::artists($result['artists'], false);
            return parent::response($artists);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }


    /**
     * Получение альбомов артиста
     * @param string $id
     * @param array $options 
     * @return object
     */
    public function getArtistAlbums (
        string $id, array $options=[]
    ) {
        // Подготавливаем перед запросов к апи
        $options['id'] = $id;
        try {
            $result = $this->spotify_artists->getArtistAlbums($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, "Artist's albums not found");
            }               
            $albums = $this->spotify_dto_factory->albums($result['items'], false);
            return parent::response($albums);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }
}