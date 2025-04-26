<?php

namespace App\DTO\Spotify\Artist;

// Трейт основы
use App\DTO\Spotify\Artist\Base;

class Get
{
    use Base;

    public ?array $images;
    public ?array $genres;
    public ?array $meta;

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
