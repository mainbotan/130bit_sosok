<?php
namespace App\Api\SC;

class Search {
    private Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function search(
        string $q,
        int $limit=20,
        int $offset=0
    ): array {
        if (empty($q)) {
            throw new \InvalidArgumentException("Query string is required");
        }
        $params = [
            'q' => $q,
            'limit' => $limit,
            'offset' => $offset
        ];

        // Чистим null-значения, чтобы не отправлять лишнее
        $params = array_filter($params, fn($v) => $v !== null);

        return $this->router->route(
            '/search/tracks', 
            $params,
            "search:{$q}:limit:{$limit}:offset:{$offset}",
            true
        )['collection'];
    }
}
