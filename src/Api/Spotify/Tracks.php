<?php
namespace App\Api\Spotify;

class Tracks {
    public function __construct(private Router $router) {}

    public function getTrack(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Track ID is required");
        }

        $endpoint = "/tracks/{$options['id']}";
        return $this->router->route($endpoint, [], "track:{$options['id']}", true);
    }

    public function getSeveralTracks(array $options): array {
        if (empty($options['ids']) || !is_array($options['ids'])) {
            throw new \InvalidArgumentException("Array of track IDs is required");
        }

        $endpoint = "/tracks";
        $params = ['ids' => implode(',', $options['ids'])];
        $params = array_filter($params, fn($v) => $v !== null);

        return $this->router->route($endpoint, $params);
    }
}
