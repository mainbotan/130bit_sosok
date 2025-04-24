<?php

namespace App\Services\Domain;

use App\Contracts\BaseContract;
use App\Repositories\AlbumsRepository as AlbumsRepository;
use App\Factories\DomainDTOFactory as DomainDTOFactory;

class AlbumsService extends BaseContract
{
    private DomainDTOFactory $domain_dto_factory;
    
    public function __construct(
        private AlbumsRepository $albums_repository
    ) {
        $this->domain_dto_factory = new DomainDTOFactory();
    }

    /**
     * Получение всех альбомов с пагинацией
     * @param array $options - массив опций (limit, offset)
     * @return mixed - ответ с коллекцией альбомов или ошибкой
     */
    public function getAllAlbums(array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->albums_repository->getAll($limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Albums not found');
            }
            
            $result = $this->domain_dto_factory::albums($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Поиск альбомов по названию или имени основного артиста
     * @param string $query - поисковая строка
     * @param array $options - массив опций (limit, offset)
     * @return mixed - ответ с коллекцией альбомов или ошибкой
     */
    public function searchAlbumsByName(string $query, array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->albums_repository->searchByName($query, $limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Albums not found');
            }
            
            $result = $this->domain_dto_factory::albums($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение альбома по ID
     * @param string $id - идентификатор альбома
     * @return mixed - ответ с альбомом или ошибкой
     */
    public function getAlbumById(string $id)
    {
        try {
            $result = $this->albums_repository->getById($id);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Album not found');
            }
            
            $result = $this->domain_dto_factory::album($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение альбома по точному названию
     * @param string $name - название альбома
     * @return mixed - ответ с альбомом или ошибкой
     */
    public function getAlbumByName(string $name)
    {
        try {
            $result = $this->albums_repository->getByName($name);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Album not found');
            }
            
            $result = $this->domain_dto_factory::album($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение нескольких альбомов по IDs
     * @param array $ids - массив идентификаторов
     * @param array $options - массив опций (limit, offset)
     * @return mixed - ответ с коллекцией альбомов или ошибкой
     */
    public function getSeveralAlbums(array $ids, array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->albums_repository->getSeveralByIds($ids, $limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Albums not found');
            }
            
            $result = $this->domain_dto_factory::albums($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Создание нового альбома
     * @param mixed $dto - DTO с данными альбома
     * @return mixed - ответ с результатом операции
     */
    public function createAlbum($dto)
    {
        try {
            $success = $this->albums_repository->create($dto);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to create album');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Обновление данных альбома
     * @param string $id - идентификатор альбома
     * @param mixed $dto - DTO с обновляемыми данными
     * @return mixed - ответ с результатом операции
     */
    public function updateAlbum(string $id, $dto)
    {
        try {
            $success = $this->albums_repository->update($id, $dto);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to update album');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Удаление альбома
     * @param string $id - идентификатор альбома
     * @return mixed - ответ с результатом операции
     */
    public function deleteAlbum(string $id)
    {
        try {
            $success = $this->albums_repository->delete($id);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to delete album');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }
}