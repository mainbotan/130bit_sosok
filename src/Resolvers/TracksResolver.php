<?php
namespace App\Resolvers;

// Интерфейс резольвера
use App\Resolvers\Concerns\BaseResolver as BaseResolver;

class TracksResolver extends BaseResolver
{
    protected function routes(): array
    {
        return [
            // Groups
            'spotify' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Track\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Track\GetByName::class,
                ],
                'getSeveral' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Tracks\GetSeveral::class,
                ],
            ],
            'genius' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Genius\Track\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Genius\Track\GetByName::class,
                ]
            ],
            'sc' => [
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\SC\Track\GetByName::class,
                ]
            ],
            'storage' => [
                'getById' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Track\GetById::class,
                ],
                'getByName' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Track\GetByName::class,
                ],
                'getAll' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Tracks\GetCollection::class,
                ],
                'getSeveral' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Domain\Tracks\GetSeveral::class,
                ]
            ]
        ];
    }

    public function __construct()
    {
        $this->routes = $this->routes();
    }
}
