<?php

namespace App\DTO;

class PlaylistUpdateDTO
{
    private array $fields = [];

    public function __construct(array $data)
    {
        $allowed = [
            'uri',
            'name',
            'description',
            'collaborative',
            'owner',
            'owner_id',
            'owner_name',
            'images',
            'tracks',
            'followers',
            'genres',
            'meta',
            'popularity',
            'snapshot_id',
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
