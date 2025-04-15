<?php

namespace App\Models;

class Artist
{
    public string $id;
    public string $uri;
    public string $name;
    public bool $is_verified;
    public bool $is_tracking;
    public int $followers;
    public array $images;
    public ?array $genres;
    public array $meta;
    public int $popularity;
    public string $created_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->uri = $data['uri'];
        $this->name = $data['name'];
        $this->is_verified = (bool) $data['is_verified'];
        $this->is_tracking = (bool) $data['is_tracking'];
        $this->followers = (int) $data['followers'];
        $this->images = json_decode($data['images'], true);
        $this->genres = isset($data['genres']) ? json_decode($data['genres'], true) : null;
        $this->meta = isset($data['meta']) ? json_decode($data['meta'], true) : [];
        $this->popularity = (int) ($data['popularity'] ?? 30);
        $this->created_at = $data['created_at'];
    }
}
