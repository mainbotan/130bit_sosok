<?php

namespace App\Factories;

use App\Models\Artist;
use App\Models\Album;
use App\Models\Playlist;
use App\Models\Track;

class DomainDTOFactory
{
    // Одиночные объекты
    public static function artist(array $data): Artist
    {
        return new Artist($data);
    }
    public static function album(array $data): Album
    {
        return new Album($data);
    }
    public static function playlist(array $data): Playlist
    {
        return new Playlist($data);
    }
    public static function track(array $data): Track
    {
        return new Track($data);
    }

    // Коллекции
    public static function artists(array $items): array
    {
        return array_map(
            fn(array $item) => new Artist($item),
            $items
        );
    }
    public static function albums(array $items): array
    {
        return array_map(
            fn(array $item) => new Album($item),
            $items
        );
    }
    public static function tracks(array $items): array
    {
        return array_map(
            fn(array $item) => new Track($item),
            $items
        );
    }
    public static function playlists(array $items): array
    {
        return array_map(
            fn(array $item) => new Playlist($item),
            $items
        );
    }
}