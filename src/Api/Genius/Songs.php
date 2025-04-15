<?php
namespace App\Api\Genius;

class Songs {
    private Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * Получение песни
     * @param string $id - gn id песни
     * @param string $text_format - plain/dom/html
     * @return array
     */
    public function getSong(string $id, string $text_format='plain'): array {
        $params = [
            'id' => $id,
            'text_format' => $text_format
        ];
        // Чистим null-значения, чтобы не отправлять лишнее
        $params = array_filter($params, fn($v) => $v !== null);

        $result = $this->router->route(
            "/songs/{$params['id']}", 
            $params, 
            "song:{$params['id']}:{$params['text_format']}", 
            true);
        return $result['song'];
    }
}
