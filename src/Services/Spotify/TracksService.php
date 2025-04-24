<?php

namespace App\Services\Spotify;

use App\Api\Spotify\Tracks as SpotifyTracksApi;
use App\Contracts\BaseContract;
use App\Factories\SpotifyDTOFactory;

class TracksService extends BaseContract {
    private SpotifyDTOFactory $spotify_dto_factory;
    public function __construct(
        private SpotifyTracksApi $spotify_tracks
    ) { 
        $this->spotify_dto_factory = new SpotifyDTOFactory();
    }

    /**
     * Получение трека по ID
     * @param string $id
     * @param array $options - массив опций
     * @return object
     */
    public function getTrackById (
        string $id, array $options=[]
    ){
        // Подготавливаем перед запросов к апи
        $options['id'] = $id;
        try {
            $result = $this->spotify_tracks->getTrack($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Track not found');
            }
            $track = $this->spotify_dto_factory::track($result, false);
            return parent::response($track);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }    

    /**
     * Получение треков
     * @param array $id
     * @return object
     */
    public function getSeveralTracks (
        array $ids
    ){
        // Подготавливаем перед запросов к апи
        $options['ids'] = $ids;
        try {
            $result = $this->spotify_tracks->getSeveralTracks($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Tracks not found');
            }
            $tracks = $this->spotify_dto_factory::tracks($result['tracks'], false);
            return parent::response($tracks);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }    

}