<?php

namespace App\Models;

class Track
{
    public string $id;
    public string $uri;
    public string $name;
    public array $artists; // Массив, а не строка (см. DTO)
    public string $primary_artist_id;
    public array $album; // Массив, а не строка (см. DTO)
    public ?string $album_id;
    public bool $explicit;
    public bool $is_local;
    public ?int $disc_number;
    public ?int $track_number;
    public ?int $duration_ms;
    public ?array $genres;
    public array $meta;
    public int $popularity;
    public ?string $preview_url;
    public ?string $isrc;
    public string $created_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->uri = $data['uri'];
        $this->name = $data['name'];
        $this->artists = json_decode($data['artists'], true); // artists как массив
        $this->primary_artist_id = $data['primary_artist_id'];
        $this->album = json_decode($data['album'], true); // album как массив
        $this->album_id = $data['album_id'] ?? null;
        $this->explicit = (bool) $data['explicit'];
        $this->is_local = (bool) $data['is_local'];
        $this->disc_number = $data['disc_number'] ?? null;
        $this->track_number = $data['track_number'] ?? null;
        $this->duration_ms = $data['duration_ms'] ?? null;
        $this->genres = isset($data['genres']) ? json_decode($data['genres'], true) : null;
        $this->meta = isset($data['meta']) ? json_decode($data['meta'], true) : [];
        $this->popularity = (int) ($data['popularity'] ?? 30);
        $this->preview_url = $data['preview_url'] ?? null;
        $this->isrc = $data['isrc'] ?? null;
        $this->created_at = $data['created_at'];
    }
}
