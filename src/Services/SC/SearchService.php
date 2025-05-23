<?php

namespace App\Services\SC;

use App\Api\SC\Search as SCSearchApi;
use App\Contracts\BaseContract;
use App\Factories\SCDTOFactory;

class SearchService extends BaseContract {
    public function __construct(
        private SCSearchApi $sc_search,
        private SCDTOFactory $sc_dto_factory
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
        $limit = isset($options['limit']) ? (int) $options['limit'] : 20;
        $offset = isset($options['offset']) ? (int) $options['offset'] : 0;
        try {
            $result = $this->sc_search->search($query, $limit, $offset);
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Result not found');
            }
            $tracks = $this->sc_dto_factory::tracks($result, false);
            return parent::response($tracks);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }
}