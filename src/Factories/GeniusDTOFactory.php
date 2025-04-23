<?php

namespace App\Factories;

use App\DTO\ArtistAdvancedDTO;
use App\DTO\TrackAdvancedDTO;
use App\Models\Artist;

class GeniusDTOFactory
{
    // Одиночные объекты
    public static function artist(array $data, $encode=true): ArtistAdvancedDTO
    {
        return new ArtistAdvancedDTO($data, $encode);
    }
    public static function track(array $data, $encode=true): TrackAdvancedDTO
    {
        return new TrackAdvancedDTO($data, $encode);
    }

    // Коллекции
    public static function artists(array $items, $encode=true): array
    {
        return array_map(
            fn(array $item) => new ArtistAdvancedDTO($item, $encode),
            $items
        );
    }
    public static function tracks(array $items, $encode=true): array
    {
        return array_map(
            fn(array $item) => new TrackAdvancedDTO($item, $encode),
            $items
        );
    }
    public static function tracks_from_search(array $items, $encode=true): array
    {
        return array_map(
            fn(array $item) => new TrackAdvancedDTO($item['result'], $encode),
            $items
        );
    }
}