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
                'search' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Spotify\Search\SearchByQuery::class,
                ]
            ],
            'genius' => [
                'search' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\Genius\Search\SearchByQuery::class,
                ]
            ],
            'sc' => [
                'search' => [
                    'middlewares' => [],
                    'use_case' => \App\UseCases\SC\Search\SearchByQuery::class,
                ]
            ]
        ];
    }

    public function __construct()
    {
        $this->routes = $this->routes();
    }
}
