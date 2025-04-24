<?php

namespace App\Services\Domain;

use App\Contracts\BaseContract;
use App\Repositories\PlaylistsRepository as PlaylistsRepository;
use App\Factories\DomainDTOFactory as DomainDTOFactory;

class PlaylistsService extends BaseContract
{
    private DomainDTOFactory $domain_dto_factory;
    
    public function __construct(
        private PlaylistsRepository $playlists_repository
    ) {
        $this->domain_dto_factory = new DomainDTOFactory();
    }

    /**
     * Получение всех плейлистов с пагинацией
     * @param array $options - массив опций (limit, offset)
     * @return mixed - ответ с коллекцией плейлистов или ошибкой
     */
    public function getAllPlaylists(array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->playlists_repository->getAll($limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Playlists not found');
            }
            
            $result = $this->domain_dto_factory::playlists($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Поиск плейлистов по названию
     * @param string $query - поисковая строка
     * @param array $options - массив опций (limit, offset)
     * @return mixed - ответ с коллекцией плейлистов или ошибкой
     */
    public function searchPlaylistsByName(string $query, array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->playlists_repository->searchByName($query, $limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Playlists not found');
            }
            
            $result = $this->domain_dto_factory::playlists($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение плейлиста по ID
     * @param string $id - идентификатор плейлиста
     * @return mixed - ответ с плейлистом или ошибкой
     */
    public function getPlaylistById(string $id)
    {
        try {
            $result = $this->playlists_repository->getById($id);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Playlist not found');
            }
            
            $result = $this->domain_dto_factory::playlist($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение плейлиста по URI
     * @param string $uri - URI плейлиста
     * @return mixed - ответ с плейлистом или ошибкой
     */
    public function getPlaylistByUri(string $uri)
    {
        try {
            $result = $this->playlists_repository->getByUri($uri);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Playlist not found');
            }
            
            $result = $this->domain_dto_factory::playlist($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение плейлиста по точному названию
     * @param string $name - название плейлиста
     * @return mixed - ответ с плейлистом или ошибкой
     */
    public function getPlaylistByName(string $name)
    {
        try {
            $result = $this->playlists_repository->getByName($name);
            
            if (!$result) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Playlist not found');
            }
            
            $result = $this->domain_dto_factory::playlist($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Получение нескольких плейлистов по IDs
     * @param array $ids - массив идентификаторов
     * @param array $options - массив опций (limit, offset)
     * @return mixed - ответ с коллекцией плейлистов или ошибкой
     */
    public function getSeveralPlaylists(array $ids, array $options = [])
    {
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;
        
        try {
            $result = $this->playlists_repository->getSeveralByIds($ids, $limit, $offset);
            
            if (empty($result)) {
                return parent::response(null, self::HTTP_NOT_FOUND, 'Playlists not found');
            }
            
            $result = $this->domain_dto_factory::playlists($result);
            return parent::response($result);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Создание нового плейлиста
     * @param mixed $dto - DTO с данными плейлиста
     * @return mixed - ответ с результатом операции
     */
    public function createPlaylist($dto)
    {
        try {
            $success = $this->playlists_repository->create($dto);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to create playlist');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Обновление данных плейлиста
     * @param string $id - идентификатор плейлиста
     * @param mixed $dto - DTO с обновляемыми данными
     * @return mixed - ответ с результатом операции
     */
    public function updatePlaylist(string $id, $dto)
    {
        try {
            $success = $this->playlists_repository->update($id, $dto);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to update playlist');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }

    /**
     * Удаление плейлиста
     * @param string $id - идентификатор плейлиста
     * @return mixed - ответ с результатом операции
     */
    public function deletePlaylist(string $id)
    {
        try {
            $success = $this->playlists_repository->delete($id);
            
            if (!$success) {
                return parent::response(null, self::HTTP_BAD_REQUEST, 'Failed to delete playlist');
            }
            
            return parent::response(['success' => true]);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th->getMessage()
            );
        }
    }
}