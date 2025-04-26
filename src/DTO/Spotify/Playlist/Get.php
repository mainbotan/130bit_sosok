<?php

namespace App\DTO\Spotify\Playlist;

use App\Helpers\RemoveAvailableMarkets;

// Вложенные структуры
use App\DTO\Spotify\Track\Get as TrackGet;

class Get
{
    public string $id;
    public string $uri;
    public string $name;
    public ?string $description;

    public bool $collaborative;

    public string $owner;
    public string $owner_id;
    public string $owner_name;

    public string $images;
    public string | array | null $tracks;

    public int $followers;
    public ?string $genres;
    public string $meta;
    public int $popularity;

    public ?string $snapshot_id;

    public function __construct(array $data)
    {
        $cleaned = RemoveAvailableMarkets::clean($data);

        $this->id = $cleaned['id'];
        $this->uri = $cleaned['uri'];
        $this->name = $cleaned['name'];
        $this->description = $cleaned['description'] ?? null;

        $this->collaborative = (bool) ($cleaned['collaborative'] ?? false);

        $this->owner = json_encode($cleaned['owner'], JSON_UNESCAPED_UNICODE);
        $this->owner_id = $cleaned['owner']['id'];
        $this->owner_name = $cleaned['owner']['display_name'] ?? $cleaned['owner']['id'];

        $this->images = json_encode($cleaned['images'] ?? [], JSON_UNESCAPED_UNICODE);
        $this->followers = $cleaned['followers']['total'] ?? 0;
        $this->genres = isset($cleaned['genres']) ? json_encode($cleaned['genres'], JSON_UNESCAPED_UNICODE) : null;
        $this->meta = isset($cleaned['meta']) ? json_encode($cleaned['meta'], JSON_UNESCAPED_UNICODE) : '{}';

        $this->popularity = (int) ($cleaned['popularity'] ?? 30);

        $this->snapshot_id = $cleaned['snapshot_id'] ?? null;

        if (isset($cleaned['tracks']['items'])){
            $this->tracks = array_map(
                fn(array $item) => new TrackGet($item['track']),
                $cleaned['tracks']['items']
            ) ?? null;
        }
    }
}
