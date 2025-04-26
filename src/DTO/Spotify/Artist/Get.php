<?php

namespace App\DTO\Spotify\Artist;

class Get
{
    public string $id;
    public string $uri;
    public string $name;
    public bool $is_verified;
    public bool $is_tracking;
    public int $followers;
    public string | array | null $images;
    public string | array | null $genres;
    public string | array | null $meta;
    public int $popularity;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->uri = $data['uri'];
        $this->name = $data['name'];
        $this->is_verified = filter_var(($data['is_verified'] ?? false), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        $this->is_tracking = filter_var(($data['is_tracking'] ?? true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true;
        $this->followers = (int)($data['followers']['total'] ?? 0);
        $this->popularity = (int)($data['popularity'] ?? 30);

        $this->images = $data['images'] ?? null;
        $this->genres = $data['genres'] ?? null;
        $this->meta = $data['meta'] ?? null;
    }
}
