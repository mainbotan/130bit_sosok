<?php

namespace App\Factories;

// Get DTO
use App\DTO\Spotify\Artist\Get as ArtistGet;
use App\DTO\Spotify\Album\Get as AlbumGet;
use App\DTO\Spotify\Playlist\Get as PlaylistGet;
use App\DTO\Spotify\Track\Get as TrackGet;

// Create DTO Serializers
use App\DTO\Spotify\Album\Serialize as AlbumSerialize;
use App\DTO\Spotify\Artist\Serialize as ArtistSerialize;
use App\DTO\Spotify\Track\Serialize as TrackSerialize;
use App\DTO\Spotify\Playlist\Serialize as PlaylistSerialize;


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

    // Сериализаторы
    public static function artist_serialize(ArtistGet $data): ArtistSerialize
    {
        return new ArtistSerialize($data);
    }
    
    public static function album_serialize(AlbumGet $data): AlbumSerialize
    {
        return new AlbumSerialize($data);
    }

    public static function track_serialize(TrackGet $data): TrackSerialize
    {
        return new TrackSerialize($data);
    }

    public static function playlist_serialize(PlaylistGet $data): PlaylistSerialize
    {
        return new PlaylistSerialize($data);
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