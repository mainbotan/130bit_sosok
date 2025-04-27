<?php

namespace App\Factories;

use App\DTO\Genius\Artist\Get as ArtistGet;
use App\DTO\Genius\Track\Get as TrackGet;
use App\Models\Artist;

class GeniusDTOFactory
{
    // Одиночные объекты
    public static function artist(array $data): ArtistGet
    {
        return new ArtistGet($data);
    }
    public static function track(array $data): TrackGet
    {
        return new TrackGet($data);
    }

    // Коллекции
    public static function artists(array $items): array
    {
        return array_map(
            fn(array $item) => new ArtistGet($item),
            $items
        );
    }
    public static function tracks(array $items): array
    {
        return array_map(
            fn(array $item) => new TrackGet($item),
            $items
        );
    }
    public static function tracks_from_search(array $items): array
    {
        return array_map(
            fn(array $item) => new TrackGet($item['result']),
            $items
        );
    }
}