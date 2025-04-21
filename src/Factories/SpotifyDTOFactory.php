<?php

namespace App\Factories;

use App\DTO\ArtistCreateDTO;
use App\DTO\AlbumCreateDTO;
use App\DTO\TrackCreateDTO;

class SpotifyDTOFactory
{
    // Одиночные объекты
    public static function artist(array $data, $encode=true): ArtistCreateDTO
    {
        return new ArtistCreateDTO($data, $encode);
    }

    public static function album(array $data, $encode=true): AlbumCreateDTO
    {
        return new AlbumCreateDTO($data, $encode);
    }

    public static function track(array $data, $encode=true): TrackCreateDTO
    {
        return new TrackCreateDTO($data, $encode);
    }

    // Коллекции
    public static function artists(array $items, $encode=true): array
    {
        return array_map(
            fn(array $item) => new ArtistCreateDTO($item, $encode),
            $items
        );
    }

    public static function albums(array $items, $encode=true): array
    {
        return array_map(
            fn(array $item) => new AlbumCreateDTO($item, $encode),
            $items
        );
    }

    public static function tracks(array $items, $encode=true): array
    {
        return array_map(
            fn(array $item) => new TrackCreateDTO($item, $encode),
            $items
        );
    }
}