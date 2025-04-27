<?php

namespace App\DTO\Spotify\Playlist;

use App\Helpers\RemoveAvailableMarkets;

// Вложенные структуры
use App\DTO\Spotify\Track\Get as TrackGet;

// Трейт основы
use App\DTO\Spotify\Playlist\Base;

class Get
{
    use Base;

    public ?array $owner;
    public ?array $images;
    public ?array $tracks;
    public ?array $genres;
    public ?array $meta;

    public function __construct(array $data)
    {
        $cleaned = RemoveAvailableMarkets::clean($data);

        $this->id = $cleaned['id'];
        $this->uri = $cleaned['uri'];
        $this->name = $cleaned['name'];
        $this->description = $cleaned['description'] ?? null;

        $this->collaborative = (bool) ($cleaned['collaborative'] ?? false);

        if (isset($cleaned['owner'])) {
            $this->owner = $cleaned['owner'];
            $this->owner_id = $cleaned['owner']['id'];
            $this->owner_name = $cleaned['owner']['display_name'];
        } else {
            $this->owner = null;
            $this->owner_id = null;
            $this->owner_name = null;
        }

        $this->images = isset($cleaned['images']) ? $cleaned['images'] : null;
        $this->followers = $cleaned['followers']['total'] ?? 0;
        $this->genres = isset($cleaned['genres']) ? $cleaned['genres'] : null;
        $this->meta = isset($cleaned['meta']) ? $cleaned['meta'] : null;

        $this->popularity = isset(($cleaned['popularity'])) ? (int) $cleaned['popularity'] : 30;

        $this->snapshot_id = $cleaned['snapshot_id'] ?? null;

        if (isset($cleaned['tracks']['items'])){
            $this->tracks = array_map(
                fn(array $item) => new TrackGet($item['track']),
                $cleaned['tracks']['items']
            ) ?? null;
        } else{ $this->tracks = null; }
    }
}
