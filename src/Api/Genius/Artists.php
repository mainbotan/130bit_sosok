<?php
namespace App\Api\Genius;

class Artists {
    private Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * Получение треков артиста
     * @param string $id - gn id артиста
     * @param string $sort - тип сортировки
     * @param int $limit 
     * @param int $offset
     * @return array
     */
    public function getArtistSongs(
        string $id, 
        int $limit=20,
        int $offset=0,
        string $sort='popularity'
    ): array {
        $params = [
            'id' => $id,
            'limit' => $limit ?? 20,
            'offset' => $offset ?? 0,
            'sort' => $sort
        ];
        // Чистим null-значения, чтобы не отправлять лишнее
        $params = array_filter($params, fn($v) => $v !== null);

        $result = $this->router->route(
            "/artists/{$params['id']}/songs", 
            $params, 
            "artist:{$params['id']}:songs:limit:{$params['limit']}:offset:{$params['offset']}", 
            true);
        return $result['songs'];
    }

    /**
     * Получение артиста
     * @param string $id - gn id артиста
     * @param string $text_format - plain/dom/html
     * @return array
     */
    public function getArtist(string $id, string $text_format='plain'): array {
        $params = [
            'id' => $id,
            'text_format' => $text_format
        ];
        // Чистим null-значения, чтобы не отправлять лишнее
        $params = array_filter($params, fn($v) => $v !== null);

        $result = $this->router->route(
            "/artists/{$params['id']}", 
            $params, 
            "artist:{$params['id']}:{$params['text_format']}", 
            true);
        return $result['artist'];
    }
}
