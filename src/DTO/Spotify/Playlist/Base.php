<?php

namespace App\DTO\Spotify\Playlist;

// Целевой DTO
use App\DTO\Spotify\Playlist\Get as PlaylistGet;
use App\Models\Playlist;

trait Base
{
    public string $id;
    public string $uri;
    public string $name;
    public ?string $description;
    public bool $collaborative;
    public ?string $owner_id;
    public ?string $owner_name;
    public int $popularity;
    public int $followers;
    public ?string $snapshot_id;

    public function initSerialize(PlaylistGet $object){
        $this->id = $object->id;
        $this->uri = $object->uri;
        $this->name = $object->name;
        $this->description = $object->description;
        $this->collaborative = $object->collaborative;
        $this->owner_id = $object->owner_id;
        $this->owner_name = $object->owner_name;
        $this->popularity = $object->popularity;
        $this->followers = $object->followers;
        $this->snapshot_id = $object->snapshot_id;
    }
}
