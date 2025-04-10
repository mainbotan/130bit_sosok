<?php
namespace App\Api\Spotify;

class Playlists {
    public function __construct(private Router $router) {}

    public function getPlaylist(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Playlist ID is required");
        }

        $endpoint = "/playlists/{$options['id']}";
        return $this->router->route($endpoint, [], "playlist:{$options['id']}", true);
    }

    public function getPlaylistItems(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Playlist ID is required");
        }

        $endpoint = "/playlists/{$options['id']}/tracks";
        $params = [];

        if (!empty($options['limit'])) $params['limit'] = $options['limit'];
        if (!empty($options['offset'])) $params['offset'] = $options['offset'];
        $params = array_filter($params, fn($v) => $v !== null);

        return $this->router->route($endpoint, $params);
    }
}
