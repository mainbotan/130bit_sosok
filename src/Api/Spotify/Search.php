<?php
namespace App\Api\Spotify;

class Search {
    private Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    // Поиск
    public function search(array $options): array {
        if (empty($options['query'])) {
            throw new \InvalidArgumentException("Search query is required");
        }

        if (empty($options['type'])) {
            throw new \InvalidArgumentException("Search type is required");
        }

        $params = [
            'q' => $options['query'],
            'type' =>  is_array($options['type']) ? implode(',', $options['type']) : $options['type'],
            'limit' => $options['limit'] ?? 20,
            'offset' => $options['offset'] ?? 0,
            'market' => $options['market'] ?? 'US'
        ];

        // Чистим null-значения, чтобы не отправлять лишнее
        $params = array_filter($params, fn($v) => $v !== null);

        return $this->router->route(
            '/search', $params,
            "search:{$params['q']}:{$params['type']}:{$params['limit']}:{$params['offset']}:{$params['market']}",
            true
        );
    }
}
