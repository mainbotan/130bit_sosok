<?php

namespace App\DTO\Spotify\Album;

use App\Helpers\RemoveAvailableMarkets;

// Вложенные структуры
use App\DTO\Spotify\Artist\Get as ArtistGet;
use App\DTO\Spotify\Track\Get as TrackGet;

class Get
{
    public string $id;
    public string $uri;
    public string $name;
    public string | array | null $artists;
    public string $primary_artist_id;
    public string $primary_artist_name;
    public string | array | null $images;
    public ?string $upc;
    public string $release_date;
    public int $total_tracks;
    public string | array | null $tracks;
    public string | array | null $genres;
    public ?string $label;
    public string | array | null $meta;
    public int $popularity;

    public function __construct(array $data)
    {
        $cleaned = RemoveAvailableMarkets::clean($data);

        $this->id = $cleaned['id'];
        $this->uri = $cleaned['uri'];
        $this->name = $cleaned['name'];
        $this->primary_artist_id = $cleaned['artists'][0]['id'];
        $this->primary_artist_name = $cleaned['artists'][0]['name'];
        $this->upc = $cleaned['external_ids']['upc'] ?? null;
        $this->release_date = $cleaned['release_date'];
        $this->total_tracks = (int) $cleaned['total_tracks'];
        $this->label = $cleaned['label'] ?? null;
        $this->popularity = (int) ($cleaned['popularity'] ?? 30);
    
        if (isset($cleaned['artists'])) {
            $this->artists = array_map(
                fn(array $item) => new ArtistGet($item),
                $cleaned['artists']
            ) ?? null;
        }
        if (isset($cleaned['tracks'])) {
            $this->tracks = array_map(
                fn(array $item) => new TrackGet($item),
                $cleaned['tracks']['items']
            ) ?? null;
        }
        $this->images = $cleaned['images'];
        $this->genres = $cleaned['genres'] ?? null;
        $this->meta = $cleaned['meta'] ?? null;
    }
}
