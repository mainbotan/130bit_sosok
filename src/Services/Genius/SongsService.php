<?php

namespace App\Services\Genius;

use App\Services\BaseService as BaseService;
use App\Api\Genius\Songs as GeniusSongsApi;
use App\Factories\GeniusDTOFactory;

class SongsService extends BaseService{
    private GeniusDTOFactory $genius_dto_factory;
    public function __construct(
        private GeniusSongsApi $genius_songs
    ) { 
        $this->genius_dto_factory = new GeniusDTOFactory();
    }

    /**
     * Получение трека по ID
     * @param string $id
     * @param array $options - массив опций
     * @return object
     */
    public function getSongById (
        string $id, array $options=[]
    ){
        $text_format = isset($options['text_format']) ? $options['text_format'] : 'plain';
        try {
            $result = $this->genius_songs->getSong($id, $text_format);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Song not found');
            }
            $track = $this->genius_dto_factory::track($result, false);
            return parent::response($track);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }
}