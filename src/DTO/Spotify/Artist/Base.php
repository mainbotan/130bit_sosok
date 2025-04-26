<?php

namespace App\DTO\Spotify\Artist;

// Целевой DTO
use App\DTO\Spotify\Artist\Get as ArtistGet;

trait Base
{
    public string $id;
    public string $uri;
    public string $name;
    public bool $is_verified;
    public bool $is_tracking;
    public int $followers;
    public int $popularity;

    public function initSerialize(ArtistGet $object){
        $this->id = $object->id;
        $this->uri = $object->uri;
        $this->name = $object->name;
        $this->is_verified = $object->is_verified;
        $this->is_tracking = $object->is_tracking;
        $this->followers = $object->followers;
        $this->popularity = $object->popularity;
    }
}
