<?php

namespace App\DTO\Spotify\Album;

// Целевой DTO
use App\DTO\Spotify\Album\Get as AlbumGet;

trait Base
{
    public string $id;
    public string $uri;
    public string $name;
    public string $primary_artist_id;
    public string $primary_artist_name;
    public ?string $upc;
    public string $release_date;
    public int $total_tracks;
    public ?string $label;
    public int $popularity;

    public function initSerialize(AlbumGet $object){
        $this->id = $object->id;
        $this->uri = $object->uri;
        $this->name = $object->name;
        $this->primary_artist_id = $object->primary_artist_id;
        $this->primary_artist_name = $object->primary_artist_name;
        $this->upc = $object->upc;
        $this->release_date = $object->release_date;
        $this->total_tracks = $object->total_tracks;
        $this->label = $object->label;
        $this->popularity = $object->popularity;
    }
}
