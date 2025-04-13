<?php

namespace App\Repositories;

use PDO;
use App\DTO\AlbumCreateDTO;
use App\DTO\UpdateAlbumDTO;
use App\Models\Album as AlbumModel;

class AlbumRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    /**
     * Поиск по name + primary_artist_name
     * @param string query - поисковая строка
     * @return array|null
     */
    public function searchByName(string $query, int $limit = 20, int $offset = 0): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM albums 
            WHERE name ILIKE :query OR primary_artist_name ILIKE :query
            ORDER BY popularity DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue('query', '%' . $query . '%');
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new AlbumModel($row), $results);
    }

    /**
     * Удаление
     * @param int $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM albums
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Изменение полей
     * @param int $id
     * @param array $fields
     * @return bool 
     */
    public function update(string $id, UpdateAlbumDTO $dto): bool
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
            UPDATE albums
            SET $setClause
            WHERE id = :id
        ");

        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Получение по имени (точное совпадение)
     * @param string $name 
     * @return AlbumModel|null
     */
    public function getByName(string $name): ?AlbumModel
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM albums 
            WHERE name = :name 
            LIMIT 1
        ");

        $stmt->execute(['name' => $name]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? new AlbumModel($result) : null;
    }

    /**
     * Получение из списка id
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

        // Генерим подстановки для безопасного IN-запроса
        $placeholders = [];
        $params = [];

        foreach ($ids as $index => $id) {
            $key = ":id$index";
            $placeholders[] = $key;
            $params[$key] = $id;
        }

        $inClause = implode(',', $placeholders);

        // Добавляем лимит и оффсет
        $stmt = $this->pdo->prepare("
            SELECT * FROM albums 
            WHERE id IN ($inClause)
            ORDER BY popularity DESC
            LIMIT :limit OFFSET :offset
        ");

        // Прокидываем значения лимита и оффсета
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new AlbumModel($row), $results);
    }

    /**
     * Выборка по Id
     * @param string $id
     * @return array
     */
    public function getById(string $id): ?AlbumModel
    {   
        $stmt = $this->pdo->prepare("
            SELECT * FROM albums
            WHERE id=:id
        ");
        $stmt->execute([
            'id' => $id
        ]);
        if ($stmt->rowCount() == 1){
            return new AlbumModel($stmt->fetch(PDO::FETCH_ASSOC));
        }
        return null;
    }

    /**
     * Выборка всех альбомов
     * @param int $limit 
     * @param int $offset
     * @return array
     */
    public function getAll(int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM albums 
            ORDER BY id DESC 
            LIMIT :limit OFFSET :offset 
        ");
        $stmt->execute([
            'limit' => $limit,
            'offset' => $offset
        ]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new AlbumModel($row), $rows);
    }

    /**
     * Создание записи альбома
     * @param AlbumCreateDto $dto - объект дто
     * @return bool - результат сохранения
     */
    public function create(AlbumCreateDTO $dto): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO albums (
                id, uri, name, artists, images, upc,
                release_date, total_tracks, tracks, genres,
                label, meta, popularity, 
                primary_artist_id, primary_artist_name
            ) VALUES (
                :id, :uri, :name, :artists, :images, :upc,
                :release_date, :total_tracks, :tracks, :genres,
                :label, :meta, :popularity,
                :primary_artist_id, :primary_artist_name
            )
        ");
        $stmt->execute([
            'id' => $dto->id,
            'uri' => $dto->uri,
            'name' => $dto->name,
            'artists' => $dto->artists,
            'images' => $dto->images,
            'upc' => $dto->upc,
            'release_date' => $dto->release_date,
            'total_tracks' => $dto->total_tracks,
            'tracks' => $dto->tracks,
            'genres' => $dto->genres,
            'label' => $dto->label,
            'meta' => $dto->meta,
            'popularity' => $dto->popularity,
            'primary_artist_id' => $dto->primary_artist_id,
            'primary_artist_name' => $dto->primary_artist_name
        ]);
        return $stmt->rowCount() > 0;
    }
}
