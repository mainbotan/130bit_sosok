<?php

namespace App\Models;

class Playlist
{
    public string $id;
    public string $uri;
    public string $name;
    public ?string $description;

    public bool $collaborative;

    public array $owner;
    public string $owner_id;
    public string $owner_name;

    public array $images;
    public array $tracks;

    public int $followers;
    public ?array $genres;
    public array $meta;
    public int $popularity;

    public ?string $snapshot_id;

    public string $created_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->uri = $data['uri'];
        $this->name = $data['name'];
        $this->description = $data['description'] ?? null;

        $this->collaborative = (bool) ($data['collaborative'] ?? false);

        $this->owner = json_decode($data['owner'], true);
        $this->owner_id = $data['owner_id'];
        $this->owner_name = $data['owner_name'];

        $this->images = json_decode($data['images'], true);
        $this->tracks = json_decode($data['tracks'], true);

        $this->followers = (int) ($data['followers'] ?? 0);
        $this->genres = isset($data['genres']) ? json_decode($data['genres'], true) : null;
        $this->meta = isset($data['meta']) ? json_decode($data['meta'], true) : [];

        $this->popularity = (int) ($data['popularity'] ?? 30);
        $this->snapshot_id = $data['snapshot_id'] ?? null;

        $this->created_at = $data['created_at'];
    }
}
