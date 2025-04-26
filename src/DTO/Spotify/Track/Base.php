<?php

namespace App\DTO\Spotify\Track;

// Целевой DTO
use App\DTO\Spotify\Track\Get as TrackGet;

trait Base
{
    public string $id;
    public string $uri;
    public string $name;
    public string $primary_artist_id;
    public ?string $album_id;
    public bool $explicit;
    public bool $is_local;
    public ?int $disc_number;
    public ?int $track_number;
    public ?int $duration_ms;
    public int $popularity;
    public ?string $preview_url;
    public ?string $isrc;

    public function initSerialize(TrackGet $object){
        $this->id = $object->id;
        $this->uri = $object->uri;
        $this->name = $object->name;
        $this->primary_artist_id = $object->primary_artist_id;
        $this->album_id = $object->album_id;
        $this->explicit = $object->explicit;
        $this->is_local = $object->is_local;
        $this->disc_number = $object->disc_number;
        $this->track_number = $object->track_number;
        $this->duration_ms = $object->duration_ms;
        $this->popularity = $object->popularity;
        $this->preview_url = $object->preview_url;
        $this->isrc = $object->isrc;
    }
}
