<?php
namespace App\Repositories;

use PDO;
use App\DTO\ArtistCreateDTO;
use App\DTO\ArtistUpdateDTO;
use App\Models\Artist as ArtistModel;

class ArtistRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    /**
     * Поиск по имени
     * @param string $query - поисковая строка
     * @return array|null
     */
    public function searchByName(string $query, int $limit = 20, int $offset = 0): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM artists 
            WHERE name ILIKE :query OR uri ILIKE :query
            ORDER BY popularity DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue('query', '%' . $query . '%');
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new ArtistModel($row), $results);
    }

    /**
     * Удаление артиста
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM artists
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Изменение данных артиста
     * @param string $id
     * @param ArtistUpdateDTO $dto
     * @return bool 
     */
    public function update(string $id, ArtistUpdateDTO $dto): bool
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
            UPDATE artists
            SET $setClause
            WHERE id = :id
        ");

        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Получение артиста по имени (точное совпадение)
     * @param string $name 
     * @return ArtistModel|null
     */
    public function getByName(string $name): ?ArtistModel
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM artists 
            WHERE name = :name 
            LIMIT 1
        ");

        $stmt->execute(['name' => $name]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? new ArtistModel($result) : null;
    }

    /**
     * Получение списка артистов по id
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

        // Добавляем лимит и оффсет
        $stmt = $this->pdo->prepare("
            SELECT * FROM artists
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

        return array_map(fn($row) => new ArtistModel($row), $results);
    }

    /**
     * Получение артиста по Id
     * @param string $id
     * @return ArtistModel|null
     */
    public function getById(string $id): ?ArtistModel
    {   
        $stmt = $this->pdo->prepare("
            SELECT * FROM artists
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() == 1) {
            return new ArtistModel($stmt->fetch(PDO::FETCH_ASSOC));
        }
        return null;
    }

    /**
     * Получение всех артистов
     * @param int $limit 
     * @param int $offset
     * @return array
     */
    public function getAll(int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM artists
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->execute(['limit' => $limit, 'offset' => $offset]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new ArtistModel($row), $rows);
    }

    /**
     * Создание записи артиста
     * @param ArtistCreateDTO $dto - объект DTO
     * @return bool - результат сохранения
     */
    public function create(ArtistCreateDTO $dto): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO artists (
                id, uri, name, is_verified, is_tracking, followers, images, genres, meta, 
                popularity
            ) VALUES (
                :id, :uri, :name, :is_verified, :is_tracking, :followers, :images, :genres, :meta, 
                :popularity
            )
        ");
        $stmt->bindValue('id', $dto->id);
        $stmt->bindValue('uri', $dto->uri);
        $stmt->bindValue('name', $dto->name);
        $stmt->bindValue('is_verified', $dto->is_verified, PDO::PARAM_BOOL);
        $stmt->bindValue('is_tracking', $dto->is_tracking, PDO::PARAM_BOOL);
        $stmt->bindValue('followers', $dto->followers, PDO::PARAM_INT);
        $stmt->bindValue('images', $dto->images);
        $stmt->bindValue('genres', $dto->genres);
        $stmt->bindValue('meta', $dto->meta);
        $stmt->bindValue('popularity', $dto->popularity, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
