<?php

namespace App\Repositories;

use PDO;
use App\DTO\AlbumCreateDTO;
use App\Models\Album as AlbumModel;

class AlbumRepository
{
    public function __construct(
        private PDO $pdo
    ) {}
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
