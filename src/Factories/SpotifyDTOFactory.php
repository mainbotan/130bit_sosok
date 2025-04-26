<?php

namespace App\Factories;

use App\DTO\Spotify\Artist\Get as ArtistGet;
use App\DTO\Spotify\Album\Get as AlbumGet;
use App\DTO\Spotify\Playlist\Get as PlaylistGet;
use App\DTO\Spotify\Track\Get as TrackGet;
use App\Models\Playlist;

class SpotifyDTOFactory
{
    // Одиночные объекты
    public static function artist(array $data): ArtistGet
    {
        return new ArtistGet($data);
    }

    public static function album(array $data): AlbumGet
    {
        return new AlbumGet($data);
    }

    public static function track(array $data): TrackGet
    {
        return new TrackGet($data);
    }

    public static function playlist(array $data): PlaylistGet
    {
        return new PlaylistGet($data);
    }

    // Коллекции
    public static function artists(array $items): array
    {
        return array_map(
            fn(array $item) => new ArtistGet($item),
            $items
        );
    }

    public static function albums(array $items): array
    {
        return array_map(
            fn(array $item) => new AlbumGet($item),
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

    public static function playlists(array $items): array
    {
        return array_map(
            fn(array $item) => new PlaylistGet($item),
            $items
        );
    }

    // Специальные коллекции

    public static function playlist_tracks(array $items): array
    {
        return array_map(
            fn(array $item) => new TrackGet($item['track']),
            $items
        );
    }
}