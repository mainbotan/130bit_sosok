<?php

namespace App\Services\Spotify;

use App\Services\BaseService as BaseService;
use App\Api\Spotify\Albums as SpotifyAlbumsApi;
use App\Factories\SpotifyDTOFactory;

class AlbumsService extends BaseService{
    private SpotifyDTOFactory $spotify_dto_factory;
    public function __construct(
        private SpotifyAlbumsApi $spotify_albums
    ) { 
        $this->spotify_dto_factory = new SpotifyDTOFactory();
    }

    /**
     * Получение альбома по ID
     * @param string $id
     * @param array $options - массив опций
     * @return object
     */
    public function getAlbumById (
        string $id, array $options=[]
    ){
        // Подготавливаем перед запросов к апи
        $options['id'] = $id;
        try {
            $result = $this->spotify_albums->getAlbum($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Album not found');
            }
            $album = $this->spotify_dto_factory::album($result, false);
            return parent::response($album);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }    

    /**
     * Получение треков альбома
     * @param string $id
     * @param array $options - массив опций
     * @return object
     */
    public function getAlbumTracks (
        string $id, array $options=[]
    ){
        // Подготавливаем перед запросов к апи
        $options['id'] = $id;
        try {
            $result = $this->spotify_albums->getAlbumTracks($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Album not found');
            }
            $tracks = $this->spotify_dto_factory::tracks($result['items'], false);
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
    public function getSeveralAlbums (
        array $ids
    ){
        // Подготавливаем перед запросов к апи
        $options['ids'] = $ids;
        try {
            $result = $this->spotify_albums->getSeveralAlbums($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Albums not found');
            }
            $albums = $this->spotify_dto_factory::albums($result['albums'], false);
            return parent::response($albums);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }  
}