<?php

namespace App\Repositories;

use PDO;

class PlaylistsRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    /**
     * Поиск плейлистов по имени или URI
     * @param string $query - поисковая строка
     * @param int $limit - лимит
     * @param int $offset - смещение
     * @return array - массив моделей Playlist
     */
    public function searchByName(string $query, int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM playlists
            WHERE name ILIKE :query OR uri ILIKE :query
            ORDER BY popularity DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue('query', '%' . $query . '%');
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * Получение плейлиста по Id
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM playlists WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?? null;
    }

    /**
     * Получение плейлиста по URI
     * @param string $uri
     * @return array|null
     */
    public function getByUri(string $uri): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM playlists WHERE uri = :uri LIMIT 1");
        $stmt->execute(['uri' => $uri]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?? null;
    }

    /**
     * Получение плейлиста по имени (точное совпадение)
     * @param string $name
     * @return array|null
     */
    public function getByName(string $name): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM playlists WHERE name = :name LIMIT 1");
        $stmt->execute(['name' => $name]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?? null;
    }

    /**
     * Получение нескольких плейлистов по массиву Id
     * @param array $ids - массив ID
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getSeveralByIds(array $ids, int $limit = 20, int $offset = 0): array
    {
        if (empty($ids)) return [];

        // Подготовка IN-клаузы
        $placeholders = [];
        $params = [];

        foreach ($ids as $i => $id) {
            $key = ":id$i";
            $placeholders[] = $key;
            $params[$key] = $id;
        }

        $inClause = implode(',', $placeholders);

        $stmt = $this->pdo->prepare("
            SELECT * FROM playlists
            WHERE id IN ($inClause)
            ORDER BY popularity DESC
            LIMIT :limit OFFSET :offset
        ");

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }

        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * Получение всех плейлистов
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAll(int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM playlists
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Создание плейлиста
     * @param $dto
     * @return bool
     */
    public function create($dto): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO playlists (
                id, uri, name, description, collaborative, owner, owner_id, owner_name,
                images, tracks, followers, genres, meta, popularity, snapshot_id
            ) VALUES (
                :id, :uri, :name, :description, :collaborative, :owner, :owner_id, :owner_name,
                :images, :tracks, :followers, :genres, :meta, :popularity, :snapshot_id
            )
        ");

        $stmt->bindValue('id', $dto->id);
        $stmt->bindValue('uri', $dto->uri);
        $stmt->bindValue('name', $dto->name);
        $stmt->bindValue('description', $dto->description);
        $stmt->bindValue('collaborative', $dto->collaborative, PDO::PARAM_BOOL);
        $stmt->bindValue('owner', $dto->owner);
        $stmt->bindValue('owner_id', $dto->owner_id);
        $stmt->bindValue('owner_name', $dto->owner_name);
        $stmt->bindValue('images', $dto->images);
        $stmt->bindValue('tracks', $dto->tracks);
        $stmt->bindValue('followers', $dto->followers, PDO::PARAM_INT);
        $stmt->bindValue('genres', $dto->genres);
        $stmt->bindValue('meta', $dto->meta);
        $stmt->bindValue('popularity', $dto->popularity, PDO::PARAM_INT);
        $stmt->bindValue('snapshot_id', $dto->snapshot_id);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Обновление плейлиста
     * @param string $id
     * @param $dto
     * @return bool
     */
    public function update(string $id, $dto): bool
    {
        $fields = $dto->getFields();
        if (empty($fields)) return false;

        $setParts = [];
        $params = ['id' => $id];

        foreach ($fields as $key => $value) {
            $setParts[] = "$key = :$key";
            $params[$key] = $value;
        }

        $setClause = implode(', ', $setParts);
        $stmt = $this->pdo->prepare("
            UPDATE playlists
            SET $setClause
            WHERE id = :id
        ");

        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    /**
     * Удаление плейлиста
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM playlists WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
