<?php

namespace App\Services\Genius;

use App\Api\Genius\Search as GeniusSearchApi;
use App\Contracts\BaseContract;
use App\Factories\GeniusDTOFactory;

class SearchService extends BaseContract {
    public function __construct(
        private GeniusSearchApi $genius_search,
        private GeniusDTOFactory $genius_dto_factory
    ) { }

    /**
     * Поиск
     * @param string $query
     * @param array $options - массив опций
     * @return object
     */
    public function search (
        string $query, array $options=[]
    ){
        try {
            $result = $this->genius_search->search($query, $options);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Result not found');
            }
            if (isset($options['type'])) {
                $type = $options['type'];
            } else {
                $type = 'track';
            }
            switch ($type) {
                case 'track':
                default:
                    $result = $this->searchTrack($result['hits']);
            }
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }
    protected function searchTrack (array $tracks) {
        return $this->genius_dto_factory::tracks_from_search($tracks, false);
    }
}