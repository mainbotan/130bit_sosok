<?php

namespace App\Api\Spotify;

use App\Core\RedisCache;

class Album {
    private RedisCache $cache;
    private string $access_token;
    private string $api_url;

    public function __construct(string $access_token, string $api_url)
    {
        $this->access_token = $access_token;
        $this->cache = new RedisCache();
        $this->api_url = rtrim($api_url, '/'); // убираем слэш, если есть
    }

    private function route(string $endpoint, array $params = []): array
    {
        $url = $this->api_url . $endpoint;

        // Добавляем query string если нужно
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->access_token}",
                "Content-Type: application/json"
            ],
            CURLOPT_TIMEOUT => 10,
        
            // 💥 Прокси через Tor
            CURLOPT_PROXY => 'tor:9050',            // имя контейнера tor
            CURLOPT_PROXYPORT => 9050,
            CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5_HOSTNAME,
        ]); 

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("CURL error: $curlError");
        }

        $parsed = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("JSON decode error: " . json_last_error_msg());
        }

        if ($httpCode >= 400) {
            $errorMsg = $parsed['error']['message'] ?? 'Unknown error';
            throw new \Exception("Spotify API error ($httpCode): $errorMsg");
        }

        return $parsed;
    }

    /**
     * Получить альбом по ID
     * @link https://developer.spotify.com/documentation/web-api/reference/get-an-album
     */
    public function getAlbum(array $options): array
    {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Album ID is required");
        }

        $endpoint = "/albums/{$options['id']}";
        return $this->route($endpoint);
        // return $this->cache->remember("spotify-album-{$options['id']}", 3600, function () use ($endpoint) {
        //     return json_encode($this->route($endpoint));
        // });
    }

    /**
     * Получить несколько альбомов по ID
     * @link https://developer.spotify.com/documentation/web-api/reference/get-multiple-albums
     */
    public function getSeveralAlbums(array $options): array
    {
        if (empty($options['ids']) || !is_array($options['ids'])) {
            throw new \InvalidArgumentException("Array of album IDs is required");
        }

        $endpoint = "/albums";
        $params = ['ids' => implode(',', $options['ids'])];

        return $this->route($endpoint, $params);
    }

    /**
     * Получить треки альбома
     * @link https://developer.spotify.com/documentation/web-api/reference/get-an-albums-tracks
     */
    public function getAlbumTracks(array $options): array
    {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Album ID is required");
        }

        $endpoint = "/albums/{$options['id']}/tracks";
        $params = [];

        if (!empty($options['limit'])) $params['limit'] = $options['limit'];
        if (!empty($options['offset'])) $params['offset'] = $options['offset'];

        return $this->route($endpoint, $params);
    }
}
