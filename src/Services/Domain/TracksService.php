<?php

namespace App\Services\Domain;

use App\Contracts\BaseContract;
use App\Repositories\TracksRepository as TracksRepository;
use App\Factories\DomainDTOFactory as DomainDTOFactory;

class TracksService extends BaseContract
{
    private DomainDTOFactory $domain_dto_factory;
    
    public function __construct(
        private TracksRepository $tracks_repository
    ) {
        $this->domain_dto_factory = new DomainDTOFactory();
    }

    /**
     * Получение всех треков с пагинацией
     * @param array $options - массив опций (limit, offset)
     * @return mixed - ответ с коллекцией треков или ошибкой
     */
    public function getAllTracks(array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->tracks_repository->getAll($limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Tracks not found');
            }
            
            $result = $this->domain_dto_factory::tracks($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Поиск треков по названию
     * @param string $query - поисковая строка
     * @param array $options - массив опций (limit, offset)
     * @return mixed - ответ с коллекцией треков или ошибкой
     */
    public function searchTracksByName(string $query, array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->tracks_repository->searchByName($query, $limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Tracks not found');
            }
            
            $result = $this->domain_dto_factory::tracks($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение трека по ID
     * @param string $id - идентификатор трека
     * @return mixed - ответ с треком или ошибкой
     */
    public function getTrackById(string $id)
    {
        try {
            $result = $this->tracks_repository->getById($id);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Track not found');
            }
            
            $result = $this->domain_dto_factory::track($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение трека по URI
     * @param string $uri - URI трека
     * @return mixed - ответ с треком или ошибкой
     */
    public function getTrackByUri(string $uri)
    {
        try {
            $result = $this->tracks_repository->getByUri($uri);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Track not found');
            }
            
            $result = $this->domain_dto_factory::track($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение трека по точному названию
     * @param string $name - название трека
     * @return mixed - ответ с треком или ошибкой
     */
    public function getTrackByName(string $name)
    {
        try {
            $result = $this->tracks_repository->getByName($name);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Track not found');
            }
            
            $result = $this->domain_dto_factory::track($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение нескольких треков по IDs
     * @param array $ids - массив идентификаторов
     * @param array $options - массив опций (limit, offset)
     * @return mixed - ответ с коллекцией треков или ошибкой
     */
    public function getSeveralTracks(array $ids, array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->tracks_repository->getSeveralByIds($ids, $limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Tracks not found');
            }
            
            $result = $this->domain_dto_factory::tracks($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Создание нового трека
     * @param mixed $dto - DTO с данными трека
     * @return mixed - ответ с результатом операции
     */
    public function createTrack($dto)
    {
        try {
            $success = $this->tracks_repository->create($dto);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to create track');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Обновление данных трека
     * @param string $id - идентификатор трека
     * @param mixed $dto - DTO с обновляемыми данными
     * @return mixed - ответ с результатом операции
     */
    public function updateTrack(string $id, $dto)
    {
        try {
            $success = $this->tracks_repository->update($id, $dto);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to update track');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Удаление трека
     * @param string $id - идентификатор трека
     * @return mixed - ответ с результатом операции
     */
    public function deleteTrack(string $id)
    {
        try {
            $success = $this->tracks_repository->delete($id);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to delete track');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }
}