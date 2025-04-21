<?php

namespace App\DTO;

class ArtistCreateDTO
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

    public function __construct(array $data, $encode = true)
    {
        $this->id = $data['id'];
        $this->uri = $data['uri'];
        $this->name = $data['name'];
        $this->is_verified = filter_var(($data['is_verified'] ?? false), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        $this->is_tracking = filter_var(($data['is_tracking'] ?? true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true;
        $this->followers = (int)($data['followers']['total'] ?? 0);
        $this->popularity = (int)($data['popularity'] ?? 30);

        if ($encode) {
            $this->images = json_encode($data['images'] ?? [], JSON_UNESCAPED_UNICODE);
            $this->genres = isset($data['genres']) ? json_encode($data['genres'], JSON_UNESCAPED_UNICODE) : null;
            $this->meta = isset($data['meta']) ? json_encode($data['meta'], JSON_UNESCAPED_UNICODE) : '{}';
        } else {
            $this->images = $data['images'] ?? null;
            $this->genres = $data['genres'] ?? null;
            $this->meta = $data['meta'] ?? null;
        }
    }
}
