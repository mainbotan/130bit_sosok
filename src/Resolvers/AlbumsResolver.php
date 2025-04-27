<?php
namespace App\Resolvers;

// Интерфейс резольвера
use App\Resolvers\Concerns\BaseResolver as BaseResolver;

class AlbumsResolver extends BaseResolver
{
    protected function routes(): array
    {
        return [
            // Groups
            'spotify' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Album\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Album\GetByName::class,
                ],
                'getTracks' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Album\GetTracks::class,
                ],
                'getSeveral' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Albums\GetSeveral::class,
                ],
            ],
            'storage' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Album\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Album\GetByName::class,
                ],
                'getAll' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Albums\GetCollection::class,
                ],
                'getSeveral' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Albums\GetSeveral::class,
                ]
            ]
        ];
    }

    public function __construct()
    {
        $this->routes = $this->routes();
    }
}
