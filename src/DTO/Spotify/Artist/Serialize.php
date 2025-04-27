<?php

namespace App\DTO\Spotify\Artist;

use App\DTO\Concerns\ArraySerializable;
use App\DTO\Concerns\BaseSerializer;

// Трейт основы
use App\DTO\Spotify\Artist\Base;

// Целевой объект
use App\DTO\Spotify\Artist\Get as ArtistGet;

class Serialize extends BaseSerializer implements ArraySerializable
{
    use Base;

    public ?string $images;
    public ?string $genres;
    public ?string $meta;

    public function __construct(ArtistGet $object)
    {
        $this->initSerialize($object);

        $this->meta = $this->safeJson($object->meta);
        $this->images = $this->safeJson($object->images);
        $this->genres = $this->safeJson($object->genres);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'uri' => $this->uri,
            'name' => $this->name,
            'is_verified' => $this->is_verified,
            'is_tracking' => $this->is_tracking,
            'followers' => $this->followers,
            'popularity' => $this->popularity,
            'images' => $this->images,
            'genres' => $this->genres,
            'meta' => $this->meta,
        ];
    }
}
