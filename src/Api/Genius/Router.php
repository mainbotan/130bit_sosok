<?php
namespace App\Api\Genius;

use App\Core\RedisCache;
use App\Services\ProxyService;
use App\Services\LoggerService;

class Router {
    private string $api_url;
    private string $access_token;
    private RedisCache $cache;
    private ProxyService $proxy_service;
    private LoggerService $logger;

    public function __construct(
        RedisCache $cache,
        string $access_token,
        string $api_url,
        ProxyService $proxy_service,
        LoggerService $logger
    ) {
        $this->cache = $cache;
        $this->proxy_service = $proxy_service;
        $this->logger = $logger;
        $this->api_url = rtrim($api_url, '/');
        $this->access_token = $access_token;
    }

    public function route(
        string $endpoint,
        array $params = [],
        string $uri = '',
        bool $is_cache = false
    ): array {
        // Если URI передан, то формируем его
        if ($uri !== '') { 
            $uri = "genius:{$uri}"; 
        }
        // Проверяем кэш
        if ($is_cache) {
            $cached = $this->cache->get($uri);
            if (!empty($cached)) {
                $this->logger->info("Cache hit for {$uri}");
                return json_decode($cached, true);
            }
        }

        // Формируем URL
        $url = $this->api_url . $endpoint;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        // Подготавливаем заголовки
        $headers = [
            "Authorization: Bearer {$this->access_token}",
            "Content-Type: application/json"
        ];
        // Инициализируем cURL запрос
        $ch = curl_init($this->proxy_service->getUrl($url, $headers));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);

        // Выполняем запрос
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($response === false) {
            $this->logger->error("CURL error: {$curlError}");
            throw new \Exception("CURL error: $curlError");
        }

        // Декодируем ответ
        $parsed = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error("JSON decode error: " . json_last_error_msg());
            throw new \Exception("JSON decode error: " . json_last_error_msg());
        }

        // Обрабатываем ошибки API
        if ($httpCode >= 400 or (int) $parsed['meta']['status'] >= 400) {
            $errorMsg = $parsed['meta']['message'] ?? 'Unknown error';
            $this->logger->error("Genius API error ($httpCode): $errorMsg");
            throw new \Exception("Genius API error ($httpCode): $errorMsg");
        }

        // Сохраняем в кэш, если необходимо
        if ($is_cache) {
            $this->cache->set($uri, json_encode($parsed['response'], JSON_UNESCAPED_UNICODE), 3600);
            $this->logger->info("Cached response for {$uri}");
        }
        
        return $parsed['response'];
    }
}