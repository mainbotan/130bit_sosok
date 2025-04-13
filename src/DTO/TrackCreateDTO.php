<?php

namespace App\DTO;

use App\Helpers\RemoveAvailableMarkets;

class TrackCreateDTO
{
    public string $id;
    public string $uri;
    public string $name;

    public string $artists;
    public string $album;

    public string $primary_artist_id;
    public ?string $album_id;

    public bool $explicit;
    public bool $is_local;

    public ?int $disc_number;
    public ?int $track_number;
    public ?int $duration_ms;

    public ?string $genres;
    public string $meta;
    public int $popularity;

    public ?string $preview_url;
    public ?string $isrc;

    public function __construct(array $data)
    {
        $cleaned = RemoveAvailableMarkets::clean($data);

        $this->id = $cleaned['id'];
        $this->uri = $cleaned['uri'];
        $this->name = $cleaned['name'];

        $this->artists = json_encode($cleaned['artists'], JSON_UNESCAPED_UNICODE);
        $this->album = json_encode($cleaned['album'], JSON_UNESCAPED_UNICODE);

        $this->primary_artist_id = $cleaned['artists'][0]['id'];
        $this->album_id = $cleaned['album']['id'] ?? null;

        $this->explicit = (bool) ($cleaned['explicit'] ?? false);
        $this->is_local = (bool) ($cleaned['is_local'] ?? false);

        $this->disc_number = $cleaned['disc_number'] ?? null;
        $this->track_number = $cleaned['track_number'] ?? null;
        $this->duration_ms = $cleaned['duration_ms'] ?? null;

        $this->genres = isset($cleaned['genres']) ? json_encode($cleaned['genres'], JSON_UNESCAPED_UNICODE) : null;
        $this->meta = isset($cleaned['meta']) ? json_encode($cleaned['meta'], JSON_UNESCAPED_UNICODE) : '{}';

        $this->popularity = (int) ($cleaned['popularity'] ?? 30);

        $this->preview_url = $cleaned['preview_url'] ?? null;
        $this->isrc = $cleaned['external_ids']['isrc'] ?? null;
    }
}
