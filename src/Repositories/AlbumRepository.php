<?php

namespace App\Repositories;

use PDO;
use RuntimeException;
use App\Models\Album;
use App\DTO\AlbumCreateDTO;

class AlbumRepository{
    
    public function __construct(private PDO $pdo){}

    public function create (
        AlbumCreateDTO $dto
    ): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO albums (id, uri, name, artists)
            VALUES (:id, :uri, :name, :artists)
        ");
        $stmt->execute([
            'id' => $dto->id,
            'name' => $dto->name,
            'uri' => $dto->uri,
            'artists' => $dto->artists
        ]);
        if ($stmt->rowCount() === 0){
            return false;
        }
        return true;
    }
}