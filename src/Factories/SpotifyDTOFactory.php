<?php

namespace App\Factories;

use App\DTO\ArtistCreateDTO;
use App\DTO\AlbumCreateDTO;
use App\DTO\TrackCreateDTO;
use App\DTO\PlaylistCreateDTO;
use App\Models\Playlist;

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

    public static function playlist(array $data, $encode=true): PlaylistCreateDTO
    {
        return new PlaylistCreateDTO($data, $encode);
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
            fn(array $item) => new TrackCreateDTO($item, $encode) ?? new TrackCreateDTO($item['track'], $encode),
            $items
        );
    }

    public static function playlists(array $items, $encode=true): array
    {
        return array_map(
            fn(array $item) => new PlaylistCreateDTO($item, $encode),
            $items
        );
    }

    // Специальные коллекции

    public static function playlist_tracks(array $items, $encode=true): array
    {
        return array_map(
            fn(array $item) => new TrackCreateDTO($item['track'], $encode),
            $items
        );
    }
}