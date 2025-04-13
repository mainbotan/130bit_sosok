<?php

namespace App\DTO;

class UpdateAlbumDTO
{
    private array $fields = [];

    public function __construct(array $data)
    {
        // Пример валидации только известных полей
        $allowed = [
            'name', 'uri', 'artists', 'images', 'upc',
            'release_date', 'total_tracks', 'tracks', 'genres',
            'label', 'meta', 'popularity',
            'primary_artist_id', 'primary_artist_name'
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
