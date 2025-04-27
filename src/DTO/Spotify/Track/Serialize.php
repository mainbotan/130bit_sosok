<?php

namespace App\DTO\Spotify\Track;

use App\DTO\Concerns\ArraySerializable;
use App\DTO\Concerns\BaseSerializer;

// Трейт основы
use App\DTO\Spotify\Track\Base;

// Целевой DTO
use App\DTO\Spotify\Track\Get as TrackGet;

// Вложенные структуры
use App\DTO\Spotify\Artist\Serialize as ArtistSerialize;
use App\DTO\Spotify\Album\Serialize as AlbumSerialize;

class Serialize extends BaseSerializer implements ArraySerializable
{
    use Base;

    public ?string $genres;
    public ?string $meta;
    public ?string $artists;
    public ?string $album;

    public function __construct(TrackGet $object)
    {
        $this->initSerialize($object);

        // Простые массивы
        $this->meta = $this->safeJson($object->meta);
        $this->genres = $this->safeJson($object->genres);

        // Коллекции объектов
        $this->artists = $this->mapArraySerialize($object->artists, ArtistSerialize::class);

        // Одиночный объект
        $this->album = $this->serializeSingle($object->album, AlbumSerialize::class);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'uri' => $this->uri,
            'name' => $this->name,
            'primary_artist_id' => $this->primary_artist_id,
            'album_id' => $this->album_id,
            'explicit' => $this->explicit,
            'is_local' => $this->is_local,
            'disc_number' => $this->disc_number,
            'track_number' => $this->track_number,
            'duration_ms' => $this->duration_ms,
            'popularity' => $this->popularity,
            'preview_url' => $this->preview_url,
            'isrc' => $this->isrc,
            'genres' => $this->genres,
            'meta' => $this->meta,
            'artists' => $this->artists,
            'album' => $this->album,
        ];
    }
}
