<?php

namespace App\DTO;

use App\Helpers\RemoveAvailableMarkets;

class AlbumCreateDTO
{
    public string $id;
    public string $uri;
    public string $name;
    public string $artists;
    public string $primary_artist_id;
    public string $primary_artist_name;
    public string $images;
    public ?string $upc;
    public string $release_date;
    public int $total_tracks;
    public string $tracks;
    public ?string $genres;
    public ?string $label;
    public string $meta;
    public int $popularity;

    public function __construct(array $data)
    {
        $cleaned = RemoveAvailableMarkets::clean($data);

        $this->id = $cleaned['id'];
        $this->uri = $cleaned['uri'];
        $this->name = $cleaned['name'];
        $this->artists = json_encode($cleaned['artists'], JSON_UNESCAPED_UNICODE);
        $this->primary_artist_id = $cleaned['artists'][0]['id'];
        $this->primary_artist_name = $cleaned['artists'][0]['name'];
        $this->images = json_encode($cleaned['images'], JSON_UNESCAPED_UNICODE);
        $this->upc = $cleaned['external_ids']['upc'] ?? null;
        $this->release_date = $cleaned['release_date'];
        $this->total_tracks = (int) $cleaned['total_tracks'];
        $this->tracks = json_encode($cleaned['tracks'], JSON_UNESCAPED_UNICODE);
        $this->genres = isset($cleaned['genres']) ? json_encode($cleaned['genres'], JSON_UNESCAPED_UNICODE) : null;
        $this->label = $cleaned['label'] ?? null;
        $this->meta = isset($cleaned['meta']) ? json_encode($cleaned['meta'], JSON_UNESCAPED_UNICODE) : '{}';
        $this->popularity = (int) ($cleaned['popularity'] ?? 30);
    }
}
