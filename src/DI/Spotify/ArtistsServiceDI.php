<?php

namespace App\DI\Spotify;

use App\Services\Spotify\ArtistsService as SpotifyArtistsService;

use App\Api\Spotify\Router as SpotifyRouter;
use App\Api\Spotify\Artists as SpotifyArtistsApi;
use App\DTO\ArtistCreateDTO as ArtistCreateDTO;

class ArtistsServiceDI {
    public function __construct(
        private SpotifyRouter $spotify_router
    ){}

    public function get() {
        return new SpotifyArtistsService(
            new SpotifyArtistsApi($this->spotify_router)
        );
    }
}