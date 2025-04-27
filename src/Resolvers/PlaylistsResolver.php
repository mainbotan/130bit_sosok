<?php
namespace App\Resolvers;

// Интерфейс резольвера
use App\Resolvers\Concerns\BaseResolver as BaseResolver;

class PlaylistsResolver extends BaseResolver
{
    protected function routes(): array
    {
        return [
            // Groups
            'spotify' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Playlist\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Playlist\GetByName::class,
                ],
                'getTracks' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Playlist\GetTracks::class,
                ]
            ],
            'storage' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Playlist\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Playlist\GetByName::class,
                ],
                'getAll' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Playlists\GetCollection::class,
                ],
                'getSeveral' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Playlists\GetSeveral::class,
                ]
            ]
        ];
    }

    public function __construct()
    {
        $this->routes = $this->routes();
    }
}
