<?php
namespace App\Resolvers;

// Интерфейс резольвера
use App\Resolvers\Concerns\BaseResolver as BaseResolver;

class ArtistsResolver extends BaseResolver
{
    protected function routes(): array
    {
        return [
            // Groups
            'spotify' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Artist\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Artist\GetByName::class,
                ],
                'getAlbums' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Artist\GetAlbums::class,
                ],
                'getTopTracks' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Artist\GetTopTracks::class,
                ],
                'getSeveral' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Artists\GetSeveral::class,
                ],
            ],
            'genius' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Genius\Artist\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Genius\Artist\GetByName::class,
                ],
                'getTopTracks' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Genius\Artist\GetTracks::class,
                ]
            ],
            'storage' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Artist\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Artist\GetByName::class,
                ],
                'getAll' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Artists\GetCollection::class,
                ],
                'getSeveral' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Artists\GetSeveral::class,
                ]
            ]
        ];
    }

    public function __construct()
    {
        $this->routes = $this->routes();
    }
}
