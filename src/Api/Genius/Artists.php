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
     * @param array $options - опции 
     * @return array
     */
    public function getArtistTracks(
        string $id, 
        array $options
    ): array {
        if (empty($id)) {
            throw new \InvalidArgumentException("Artist ID is required");
        }
        $params = [
            'id' => $id,
            'limit' => $options['limit'] ?? 20,
            'offset' => $options['offset'] ?? 0,
            'sort' => $options['sort'] ?? 'popularity'
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
        if (empty($id)) {
            throw new \InvalidArgumentException("Artist ID is required");
        }
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
