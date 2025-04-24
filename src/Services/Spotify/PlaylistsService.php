<?php

namespace App\Services\Spotify;

use App\Api\Spotify\Playlists as SpotifyPlaylistsApi;
use App\Contracts\BaseContract;
use App\Factories\SpotifyDTOFactory;

class PlaylistsService extends BaseContract {
    public function __construct(
        private SpotifyPlaylistsApi $spotify_playlists,
        private SpotifyDTOFactory $spotify_dto_factory
    ) { }

    /**
     * Получение плейлиста по ID
     * @param string $id
     * @param array $options - массив опций
     * @return object
     */
    public function getPlaylistById (
        string $id, array $options=[]
    ){
        // Подготавливаем перед запросов к апи
        $options['id'] = $id;
        try {
            $result = $this->spotify_playlists->getPlaylist($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Playlist not found');
            }
            $playlist = $this->spotify_dto_factory::playlist($result, false);
            return parent::response($playlist);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }    

    /**
     * Получение треков плейлиста
     * @param string $id
     * @param array $options - массив опций
     * @return object
     */
    public function getPlaylistTracks (
        string $id, array $options=[]
    ){
        // Подготавливаем перед запросов к апи
        $options['id'] = $id;
        try {
            $result = $this->spotify_playlists->getPlaylistItems($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Playlist not found');
            }
            $tracks = $this->spotify_dto_factory::playlist_tracks($result['items'], false);
            return parent::response($tracks);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }    
}