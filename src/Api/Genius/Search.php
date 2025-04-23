<?php
namespace App\Api\Genius;

class Search {
    private Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }
    
    // Поиск
    public function search(string $query, array $params = []): array {
        // Чистим null-значения, чтобы не отправлять лишнее
        $params['q'] = $query;
        $params = array_filter($params, fn($v) => $v !== null);

        return $this->router->route('/search', $params);
    }
}
