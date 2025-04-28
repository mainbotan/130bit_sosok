<?php

namespace App\Http\Controllers;

// Контракт
use App\Contracts\BaseContract;
use App\DTO\BaseContractResponseDTO;

class MainController extends BaseContract
{
    protected array $resolvers = [
        'artists' => \App\Resolvers\ArtistsResolver::class,
        'albums' => \App\Resolvers\AlbumsResolver::class,
        'tracks' => \App\Resolvers\TracksResolver::class,
        'playlists' => \App\Resolvers\PlaylistsResolver::class,
        'search' => \App\Resolvers\SearchResolver::class
    ];

    public function execute(string $endpoint, array $options = []): BaseContractResponseDTO
    {
        $parts = explode('.', $endpoint, 2);

        if (count($parts) < 2) {
            return $this->response(null, self::HTTP_BAD_REQUEST, 'Invalid endpoint format. Expected resolver.action');
        }

        [$resolverKey, $resolverEndpoint] = $parts;

        if (!isset($this->resolvers[$resolverKey])) {
            return $this->response(null, self::HTTP_NOT_FOUND, "Resolver not found: {$resolverKey}");
        }

        $resolverClass = $this->resolvers[$resolverKey];

        if (!class_exists($resolverClass)) {
            return $this->response(null, self::HTTP_ERROR, "Resolver class does not exist: {$resolverClass}");
        }

        $resolver = new $resolverClass();

        return $resolver->execute($resolverEndpoint, $options);
    }
}
