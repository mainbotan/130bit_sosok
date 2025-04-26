<?php

namespace App\DTO\Spotify\Track;

use App\Helpers\RemoveAvailableMarkets;

// Трейт основы
use App\DTO\Spotify\Track\Base;

// Вложенные структуры
use App\DTO\Spotify\Album\Get as AlbumGet;
use App\DTO\Spotify\Artist\Get as ArtistGet;

class Get
{
    use Base; 
    
    public ?array $genres;
    public ?array $meta;
    public ?array $artists;
    public ?AlbumGet $album;

    public function __construct(array $data)
    {
        $cleaned = RemoveAvailableMarkets::clean($data);

        $this->id = $cleaned['id'];
        $this->uri = $cleaned['uri'];
        $this->name = $cleaned['name'];

        $this->primary_artist_id = $cleaned['artists'][0]['id'];
        $this->album_id = $cleaned['album']['id'] ?? null;

        $this->explicit = (bool) ($cleaned['explicit'] ?? false);
        $this->is_local = (bool) ($cleaned['is_local'] ?? false);

        $this->disc_number = $cleaned['disc_number'] ?? null;
        $this->track_number = $cleaned['track_number'] ?? null;
        $this->duration_ms = $cleaned['duration_ms'] ?? null;

        $this->popularity = (int) ($cleaned['popularity'] ?? 30);

        $this->preview_url = $cleaned['preview_url'] ?? null;
        $this->isrc = $cleaned['external_ids']['isrc'] ?? null;

        if (isset($cleaned['artists'])) {
            $this->artists = array_map(
                fn(array $item) => new ArtistGet($item),
                $cleaned['artists']
            ) ?? null;
        } else { $this->artists = null; }
        if (isset($cleaned['album'])) {
            $this->album = new AlbumGet($cleaned['album']) ?? null;
        } else{ $this->album = null; }
        
        $this->genres = $cleaned['genres'] ?? null;
        $this->meta = $cleaned['meta'] ?? null;
    }
}
