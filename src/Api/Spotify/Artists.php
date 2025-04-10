<?php
namespace App\Api\Spotify;

class Artists {
    public function __construct(private Router $router) {}

    public function getArtist(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Artist ID is required");
        }

        $endpoint = "/artists/{$options['id']}";
        return $this->router->route($endpoint, [], "artist:{$options['id']}", true);
    }

    public function getSeveralArtists(array $options): array {
        if (empty($options['ids']) || !is_array($options['ids'])) {
            throw new \InvalidArgumentException("Array of artist IDs is required");
        }

        $endpoint = "/artists";
        $params = ['ids' => implode(',', $options['ids'])];
        $params = array_filter($params, fn($v) => $v !== null);

        return $this->router->route($endpoint, $params);
    }

    public function getTopTracks(array $options): array {
        if (empty($options['id']) || empty($options['market'])) {
            throw new \InvalidArgumentException("Artist ID and market are required");
        }

        $endpoint = "/artists/{$options['id']}/top-tracks";
        $params = ['market' => $options['market'] ?? 'US'];

        $params = array_filter($params, fn($v) => $v !== null);
        return $this->router->route($endpoint, $params);
    }

    public function getArtistAlbums(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Artist ID is required");
        }

        $endpoint = "/artists/{$options['id']}/albums";
        $params = [];

        $params['include_groups'] = $options['include_groups'] ?? [];
        $params['include_groups'] = implode(',', $params['include_groups']);
        $params['market'] = $options['market'] ?? 'US';
        $params['limit'] = $options['limit'] ?? 10;
        $params['offset'] = $options['offset'] ?? 0;

        $params = array_filter($params, fn($v) => $v !== null);
        return $this->router->route($endpoint, $params);
    }
}
