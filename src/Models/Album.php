<?php

namespace App\Models;

class Album
{
    public string $id;
    public string $uri;
    public string $name;
    public array $artists;
    public ?string $primary_artist_id;
    public ?string $primary_artist_name;
    public array $images;
    public ?string $upc;
    public string $release_date;
    public int $total_tracks;
    public array $tracks;
    public ?array $genres;
    public ?string $label;
    public array $meta;
    public int $popularity;
    public string $created_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->uri = $data['uri'];
        $this->name = $data['name'];
        $this->artists = json_decode($data['artists'], true);
        $this->primary_artist_id = $data['primary_artist_id'];
        $this->primary_artist_name = $data['primary_artist_name'];
        $this->images = json_decode($data['images'], true);
        $this->upc = $data['upc'] ?? null;
        $this->release_date = $data['release_date'];
        $this->total_tracks = (int) $data['total_tracks'];
        $this->tracks = json_decode($data['tracks'], true);
        $this->genres = isset($data['genres']) ? json_decode($data['genres'], true) : null;
        $this->label = $data['label'] ?? null;
        $this->meta = isset($data['meta']) ? json_decode($data['meta'], true) : [];
        $this->popularity = (int) ($data['popularity'] ?? 30);
        $this->created_at = $data['created_at'];
    }
}
