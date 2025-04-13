<?php

namespace App\Repositories;

use PDO;
use App\DTO\TrackCreateDTO;
use App\DTO\TrackUpdateDTO;
use App\Models\Track as TrackModel;

class TrackRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    /**
     * Поиск по имени трека
     * @param string $query - поисковая строка
     * @param int $limit - лимит на количество результатов
     * @param int $offset - смещение
     * @return array|null
     */
    public function searchByName(string $query, int $limit = 20, int $offset = 0): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM tracks
            WHERE name ILIKE :query OR uri ILIKE :query
            ORDER BY popularity DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue('query', '%' . $query . '%');
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new TrackModel($row), $results);
    }

    /**
     * Получение трека по Id
     * @param string $id
     * @return TrackModel|null
     */
    public function getById(string $id): ?TrackModel
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM tracks
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() == 1) {
            return new TrackModel($stmt->fetch(PDO::FETCH_ASSOC));
        }
        return null;
    }

    /**
     * Получение трека по URI
     * @param string $uri
     * @return TrackModel|null
     */
    public function getByUri(string $uri): ?TrackModel
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM tracks
            WHERE uri = :uri
            LIMIT 1
        ");
        $stmt->execute(['uri' => $uri]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? new TrackModel($result) : null;
    }

    /**
     * Получение трека по имени (точное совпадение)
     * @param string $name
     * @return TrackModel|null
     */
    public function getByName(string $name): ?TrackModel
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM tracks
            WHERE name = :name
            LIMIT 1
        ");
        $stmt->execute(['name' => $name]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? new TrackModel($result) : null;
    }

    /**
     * Получение списка треков по массиву Id
     * @param array $ids
     * @param int $limit
     * @param int $offset
     * @return array|null
     */
    public function getSeveralByIds(array $ids, int $limit = 20, int $offset = 0): array
    {
        if (empty($ids)) {
            return [];
        }

        // Генерация подстановок для безопасного IN-запроса
        $placeholders = [];
        $params = [];

        foreach ($ids as $index => $id) {
            $key = ":id$index";
            $placeholders[] = $key;
            $params[$key] = $id;
        }

        $inClause = implode(',', $placeholders);

        $stmt = $this->pdo->prepare("
            SELECT * FROM tracks
            WHERE id IN ($inClause)
            ORDER BY popularity DESC
            LIMIT :limit OFFSET :offset
        ");

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new TrackModel($row), $results);
    }

    /**
     * Получение всех треков
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAll(int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM tracks
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new TrackModel($row), $rows);
    }

    /**
     * Создание записи о треке
     * @param TrackCreateDTO $dto - объект DTO
     * @return bool - результат сохранения
     */
    public function create(TrackCreateDTO $dto): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tracks (
                id, uri, name, artists, primary_artist_id, album, album_id, 
                explicit, is_local, disc_number, track_number, duration_ms, genres, meta, popularity, 
                preview_url, isrc
            ) VALUES (
                :id, :uri, :name, :artists, :primary_artist_id, :album, :album_id, 
                :explicit, :is_local, :disc_number, :track_number, :duration_ms, :genres, :meta, :popularity, 
                :preview_url, :isrc
            )
        ");
        
        $stmt->bindValue('id', $dto->id);
        $stmt->bindValue('uri', $dto->uri);
        $stmt->bindValue('name', $dto->name);
        $stmt->bindValue('artists', $dto->artists);
        $stmt->bindValue('primary_artist_id', $dto->primary_artist_id);
        $stmt->bindValue('album', $dto->album);
        $stmt->bindValue('album_id', $dto->album_id);
        $stmt->bindValue('explicit', $dto->explicit, PDO::PARAM_BOOL);
        $stmt->bindValue('is_local', $dto->is_local, PDO::PARAM_BOOL);
        $stmt->bindValue('disc_number', $dto->disc_number, PDO::PARAM_INT);
        $stmt->bindValue('track_number', $dto->track_number, PDO::PARAM_INT);
        $stmt->bindValue('duration_ms', $dto->duration_ms, PDO::PARAM_INT);
        $stmt->bindValue('genres', $dto->genres);
        $stmt->bindValue('meta', $dto->meta);
        $stmt->bindValue('popularity', $dto->popularity, PDO::PARAM_INT);
        $stmt->bindValue('preview_url', $dto->preview_url);
        $stmt->bindValue('isrc', $dto->isrc);
        
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Изменение данных о треке
     * @param string $id
     * @param TrackUpdateDTO $dto
     * @return bool
     */
    public function update(string $id, TrackUpdateDTO $dto): bool
    {
        $fields = $dto->getFields();

        if (empty($fields)) {
            return false;
        }

        $setParts = [];
        $params = ['id' => $id];

        foreach ($fields as $key => $value) {
            $setParts[] = "$key = :$key";
            $params[$key] = $value;
        }

        $setClause = implode(', ', $setParts);

        $stmt = $this->pdo->prepare("
            UPDATE tracks
            SET $setClause
            WHERE id = :id
        ");

        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Удаление трека
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM tracks
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
