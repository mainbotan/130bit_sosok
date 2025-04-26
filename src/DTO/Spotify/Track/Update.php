<?php

namespace App\DTO\Spotify\Track;

class Update
{
    private array $fields = [];

    public function __construct(array $data)
    {
        $allowed = [
            'uri',
            'name',
            'artists',
            'album',
            'primary_artist_id',
            'album_id',
            'explicit',
            'is_local',
            'disc_number',
            'track_number',
            'duration_ms',
            'genres',
            'meta',
            'popularity',
            'preview_url',
            'isrc'
        ];

        foreach ($allowed as $key) {
            if (array_key_exists($key, $data)) {
                $this->fields[$key] = $data[$key];
            }
        }
    }

    public function hasData(): bool
    {
        return !empty($this->fields);
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
