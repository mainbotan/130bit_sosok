<?php

namespace App\Services\Domain;

use App\Services\BaseService as BaseService;
use App\Repositories\ArtistsRepository as ArtistsRepository;
use App\Factories\DomainDTOFactory as DomainDTOFactory;

class ArtistsService extends BaseService
{
    private DomainDTOFactory $domain_dto_factory;
    
    public function __construct(
        private ArtistsRepository $artists_repository
    ) {
        $this->domain_dto_factory = new DomainDTOFactory();
    }

    /**
     * Получение всех артистов с пагинацией
     * @param array $options - массив опций (limit, offset)
     * @return object - ответ с коллекцией артистов или ошибкой
     */
    public function getAllArtists(array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->artists_repository->getAll($limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artists not found');
            }
            
            $result = $this->domain_dto_factory::artists($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Поиск артистов по имени
     * @param string $query - поисковая строка
     * @param array $options - массив опций (limit, offset)
     * @return object - ответ с коллекцией артистов или ошибкой
     */
    public function searchArtistsByName(string $query, array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->artists_repository->searchByName($query, $limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artists not found');
            }
            
            $result = $this->domain_dto_factory::artists($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение артиста по ID
     * @param string $id - идентификатор артиста
     * @return object - ответ с артистом или ошибкой
     */
    public function getArtistById(string $id)
    {
        try {
            $result = $this->artists_repository->getById($id);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artist not found');
            }
            $result = $this->domain_dto_factory::artist($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение артиста по точному имени
     * @param string $name - имя артиста
     * @return object - ответ с артистом или ошибкой
     */
    public function getArtistByName(string $name)
    {
        try {
            $result = $this->artists_repository->getByName($name);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artist not found');
            }
            
            $result = $this->domain_dto_factory::artist($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение нескольких артистов по IDs
     * @param array $ids - массив идентификаторов
     * @param array $options - массив опций (limit, offset)
     * @return object - ответ с коллекцией артистов или ошибкой
     */
    public function getSeveralArtists(array $ids, array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->artists_repository->getSeveralByIds($ids, $limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Artists not found');
            }
            
            $result = $this->domain_dto_factory::artists($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Создание нового артиста
     * @param mixed $dto - DTO с данными артиста
     * @return object - ответ с результатом операции
     */
    public function createArtist($dto)
    {
        try {
            $success = $this->artists_repository->create($dto);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to create artist');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Обновление данных артиста
     * @param string $id - идентификатор артиста
     * @param mixed $dto - DTO с обновляемыми данными
     * @return object - ответ с результатом операции
     */
    public function updateArtist(string $id, $dto)
    {
        try {
            $success = $this->artists_repository->update($id, $dto);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to update artist');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Удаление артиста
     * @param string $id - идентификатор артиста
     * @return object - ответ с результатом операции
     */
    public function deleteArtist(string $id)
    {
        try {
            $success = $this->artists_repository->delete($id);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to delete artist');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }
}