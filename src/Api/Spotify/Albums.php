<?php
namespace App\Api\Spotify;

use App\Api\Spotify\Router;
use App\Core\RedisCache;
use App\Services\ProxyService;
use App\Services\LoggerService;

class Albums {
    private Router $router;

    public function __construct(
        Router $router
    ) {
        $this->router = $router;
    }

    /**
     * Получение альбома по ID
     */
    public function getAlbum(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Album ID is required");
        }

        $endpoint = "/albums/{$options['id']}";
        return $this->router->route($endpoint, [], "album:{$options['id']}", true);
    }

    /**
     * Получение нескольких альбомов
     */
    public function getSeveralAlbums(array $options): array {
        if (empty($options['ids']) || !is_array($options['ids'])) {
            throw new \InvalidArgumentException("Array of album IDs is required");
        }

        $endpoint = "/albums";
        $params = ['ids' => implode(',', $options['ids'])];
        $params = array_filter($params, fn($v) => $v !== null);

        return $this->router->route($endpoint, $params);
    }

    /**
     * Получение треков альбома
     */
    public function getAlbumTracks(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Album ID is required");
        }

        $endpoint = "/albums/{$options['id']}/tracks";
        $params = [];

        if (!empty($options['limit'])) $params['limit'] = $options['limit'];
        if (!empty($options['offset'])) $params['offset'] = $options['offset'];
        $params = array_filter($params, fn($v) => $v !== null);
        return $this->router->route($endpoint, $params);
    }
}
