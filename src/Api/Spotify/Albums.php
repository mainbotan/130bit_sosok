<?php
namespace App\Api\Spotify;

use App\Core\RedisCache;
use App\Services\ProxyService;

class Albums {
    private string $api_url;

    public function __construct(
        private RedisCache $cache,
        private string $access_token,
        string $api_url,
        private ProxyService $proxy_service
    ) {
        $this->api_url = rtrim($api_url, '/');
    }

    private function route(string $endpoint, array $params = []): array {
        $url = $this->api_url . $endpoint;

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        $headers = [
            "Authorization: Bearer {$this->access_token}",
            "Content-Type: application/json"
        ];

        $ch = curl_init($this->proxy_service->getUrl($url, $headers));
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ];
        curl_setopt_array($ch, $options);
        

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

    public function getAlbum(array $options): array {
        if (empty($options['id'])) {
            throw new \InvalidArgumentException("Album ID is required");
        }

        $endpoint = "/albums/{$options['id']}";
        return $this->route($endpoint);
    }

    public function getSeveralAlbums(array $options): array {
        if (empty($options['ids']) || !is_array($options['ids'])) {
            throw new \InvalidArgumentException("Array of album IDs is required");
        }

        $endpoint = "/albums";
        $params = ['ids' => implode(',', $options['ids'])];

        return $this->route($endpoint, $params);
    }

    public function getAlbumTracks(array $options): array {
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
