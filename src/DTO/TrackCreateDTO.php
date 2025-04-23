<?php

namespace App\DTO;

use App\Helpers\RemoveAvailableMarkets;

// Вложенные структуры
use App\DTO\AlbumCreateDTO;
use App\DTO\ArtistCreateDTO;

class TrackCreateDTO
{
    public string $id;
    public string $uri;
    public string $name;

    public string | array | null $artists;
    public string | object | null $album;

    public string $primary_artist_id;
    public ?string $album_id;

    public bool $explicit;
    public bool $is_local;

    public ?int $disc_number;
    public ?int $track_number;
    public ?int $duration_ms;

    public string | array | null $genres;
    public string | array | null $meta;
    public int $popularity;

    public string | array | null $preview_url;
    public string | array | null $isrc;

    public function __construct(array $data, $encode = true)
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

        if ($encode) {
            $this->artists = json_encode($cleaned['artists'], JSON_UNESCAPED_UNICODE);
            $this->album = json_encode($cleaned['album'], JSON_UNESCAPED_UNICODE);

            $this->genres = isset($cleaned['genres']) ? json_encode($cleaned['genres'], JSON_UNESCAPED_UNICODE) : null;
            $this->meta = isset($cleaned['meta']) ? json_encode($cleaned['meta'], JSON_UNESCAPED_UNICODE) : '{}';
        } else {
            if (isset($cleaned['artists'])) {
                $this->artists = array_map(
                    fn(array $item) => new ArtistCreateDTO($item, $encode),
                    $cleaned['artists']
                ) ?? null;
            }
            if (isset($cleaned['album'])) {
                $this->album = new AlbumCreateDTO($cleaned['album'], $encode) ?? null;
            }
            $this->genres = $cleaned['genres'] ?? null;
            $this->meta = $cleaned['meta'] ?? null;
        }
    }
}
