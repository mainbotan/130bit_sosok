<?php

namespace App\DTO\Spotify\Album;

use App\DTO\Concerns\ArraySerializable;
use App\DTO\Concerns\BaseSerializer;

// Трейт основы
use App\DTO\Spotify\Album\Base;

// Целевой DTO
use App\DTO\Spotify\Album\Get as AlbumGet;

// Вложенные структуры
use App\DTO\Spotify\Artist\Serialize as ArtistSerialize;
use App\DTO\Spotify\Track\Serialize as TrackSerialize;

class Serialize extends BaseSerializer implements ArraySerializable
{
    use Base;

    public ?string $meta;
    public ?string $images;
    public ?string $artists;
    public ?string $tracks;
    public ?string $genres;

    public function __construct(AlbumGet $object)
    {
        $this->initSerialize($object);

        // Простые массивы
        $this->meta = $this->safeJson($object->meta);
        $this->images = $this->safeJson($object->images);
        $this->genres = $this->safeJson($object->genres);

        // Коллекции объектов
        $this->artists = $this->mapArraySerialize($object->artists, ArtistSerialize::class);
        $this->tracks = $this->mapArraySerialize($object->tracks, TrackSerialize::class);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'uri' => $this->uri,
            'name' => $this->name,
            'primary_artist_id' => $this->primary_artist_id,
            'primary_artist_name' => $this->primary_artist_name,
            'upc' => $this->upc,
            'release_date' => $this->release_date,
            'total_tracks' => $this->total_tracks,
            'label' => $this->label,
            'popularity' => $this->popularity,
            'meta' => $this->meta,
            'images' => $this->images,
            'artists' => $this->artists,
            'tracks' => $this->tracks,
            'genres' => $this->genres,
        ];
    }
}
