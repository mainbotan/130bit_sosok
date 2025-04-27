<?php

namespace App\DTO\Spotify\Playlist;


use App\DTO\Concerns\ArraySerializable;
use App\DTO\Concerns\BaseSerializer;

// Трейт основы
use App\DTO\Spotify\Playlist\Base;

// Целевой DTO
use App\DTO\Spotify\Playlist\Get as PlaylistGet;

// Вложенные структуры
use App\DTO\Spotify\Artist\Serialize as ArtistSerialize;
use App\DTO\Spotify\Track\Serialize as TrackSerialize;

use Exception;

class Serialize extends BaseSerializer implements ArraySerializable
{
    use Base;

    public ?string $owner;
    public ?string $images;
    public ?string $tracks;
    public ?string $genres;
    public ?string $meta;

    public function __construct(PlaylistGet $object)
    {
        // Autopopulating basic fields
        $this->initSerialize($object);

        // Serializing embedded fields
        $this->owner = $this->safeJson($object->owner);
        $this->images = $this->safeJson($object->images);
        $this->genres = $this->safeJson($object->genres);
        $this->meta = $this->safeJson($object->meta);
        $this->snapshot_id = $object->snapshot_id;

        // Serializing tracks
        $this->tracks = $this->mapArraySerialize($object->tracks, TrackSerialize::class);
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'uri' => $this->uri,
            'name' => $this->name,
            'description' => $this->description,
            'collaborative' => $this->collaborative,
            'owner_id' => $this->owner_id,
            'owner_name' => $this->owner_name,
            'popularity' => $this->popularity,
            'followers' => $this->followers,
            'owner' => $this->owner,
            'images' => $this->images,
            'tracks' => $this->tracks,
            'genres' => $this->genres,
            'meta' => $this->meta,
            'snapshot_id' => $this->snapshot_id,
        ];
    }
}
