<?php

namespace App\Services\Spotify;

use App\Services\BaseService as BaseService;
use App\Api\Spotify\Search as SpotifySearchApi;
use App\Factories\SpotifyDTOFactory;

class SearchService extends BaseService{
    private SpotifyDTOFactory $spotify_dto_factory;
    public function __construct(
        private SpotifySearchApi $spotify_search
    ) { 
        $this->spotify_dto_factory = new SpotifyDTOFactory();
    }

    /**
     * Поиск 
     * @param string $query - поисковая строка
     * @param array $options - массив опций
     * @return object
     */
    public function search (
        string $query, array $options=[]
    ){
        // Подготавливаем перед запросов к апи
        $options['query'] = $query;
        try {
            $result = $this->spotify_search->search($options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Result not found');
            }
            $result_output = [];
            if (isset($result['artists'])) {
                $result_output['artists'] = $this->spotify_dto_factory::artists($result['artists']['items'], false);
            }
            if (isset($result['albums'])) {
                $result_output['albums'] = $this->spotify_dto_factory::albums($result['albums']['items'], false);
            }
            if (isset($result['tracks'])) {
                $result_output['tracks'] = $this->spotify_dto_factory::tracks($result['tracks']['items'], false);
            }
            // Проблемки
            if (isset($result['playlists'])) {
                $result_output['playlists'] = $this->spotify_dto_factory::playlists($result['playlists']['items'], false);
            }
            return parent::response($result_output);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }    
}