<?php

namespace App\DTO;

use App\Helpers\RemoveAvailableMarkets;

// Вложенные структуры
use App\DTO\TrackCreateDTO;
use App\DTO\ArtistCreateDTO;

class AlbumCreateDTO
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

    public function __construct(array $data, $encode = true)
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
    
        if ($encode) {
            $this->genres = isset($cleaned['album']) ? json_encode($cleaned['album'], JSON_UNESCAPED_UNICODE) : null;
            $this->artists = isset($cleaned['artists']) ? json_encode($cleaned['artists'], JSON_UNESCAPED_UNICODE) : null;
            $this->images = json_encode($cleaned['images'], JSON_UNESCAPED_UNICODE);
            $this->genres = isset($cleaned['genres']) ? json_encode($cleaned['genres'], JSON_UNESCAPED_UNICODE) : null;
            $this->meta = isset($cleaned['meta']) ? json_encode($cleaned['meta'], JSON_UNESCAPED_UNICODE) : '{}';
        } else{
            if (isset($cleaned['artists'])) {
                $this->artists = array_map(
                    fn(array $item) => new ArtistCreateDTO($item, $encode),
                    $cleaned['artists']
                ) ?? null;
            }
            if (isset($cleaned['tracks'])) {
                $this->tracks = array_map(
                    fn(array $item) => new TrackCreateDTO($item, $encode),
                    $cleaned['tracks']
                ) ?? null;
            }
            $this->images = $cleaned['images'];
            $this->genres = $cleaned['genres'] ?? null;
            $this->meta = $cleaned['meta'] ?? null;
        }
    }
}
