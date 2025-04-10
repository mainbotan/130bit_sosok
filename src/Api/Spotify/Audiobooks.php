<?php
namespace App\Api\Spotify;

class Audiobooks {
    public function __construct(private Router $router) {}

    public function getAudiobook(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Audiobook ID is required");
        }

        $endpoint = "/audiobooks/{$options['id']}";
        return $this->router->route($endpoint, [], "audiobook:{$options['id']}", true);
    }

    public function getSeveralAudiobooks(array $options): array {
        if (empty($options['ids']) || !is_array($options['ids'])) {
            throw new \InvalidArgumentException("Array of audiobook IDs is required");
        }

        $endpoint = "/audiobooks";
        $params = ['ids' => implode(',', $options['ids'])];
        $params = array_filter($params, fn($v) => $v !== null);

        return $this->router->route($endpoint, $params);
    }

    public function getAudiobookChapters(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Audiobook ID is required");
        }

        $endpoint = "/audiobooks/{$options['id']}/chapters";
        $params = [];

        if (!empty($options['limit'])) $params['limit'] = $options['limit'];
        if (!empty($options['offset'])) $params['offset'] = $options['offset'];
        $params = array_filter($params, fn($v) => $v !== null);

        return $this->router->route($endpoint, $params);
    }
}
