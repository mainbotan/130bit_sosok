<?php

namespace App\DTO\Spotify\Artist;

class Update
{
    private array $fields = [];

    public function __construct(array $data)
    {
        // Пример валидации только известных полей
        $allowed = [
            'name', 'uri', 'is_verified', 'is_tracking', 'followers', 'images', 
            'genres', 'meta', 'popularity', 'primary_artist_id', 'primary_artist_name'
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