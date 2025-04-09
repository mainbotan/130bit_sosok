<?php
namespace App\Api\Spotify;
use App\Core\RedisCache as RedisCache;

class Autho{
    private RedisCache $cache;
    private array $spotifyConfig;
    private string $clientId;
    private string $clientSecret;
    private string $tokenUrl;

    public function __construct($spotifyConfig)
    {
        $this->spotifyConfig = $spotifyConfig;
        $this->clientId = $spotifyConfig['client_id'];
        $this->clientSecret = $spotifyConfig['client_secret'];
        $this->tokenUrl = $spotifyConfig['token_url'];
        $this->cache = new RedisCache();
    }

    public function getAccessToken(): ?string
    {
        // Исправляем вызов getAccessToken для передачи callback
        return $this->cache->remember('spotify-access-token', 3500, function() {
            return $this->requestAccessToken(); // ленивый вызов
        });
    }

    public function requestAccessToken(): ?string
    {
        $headers = [
            "Authorization: Basic " . base64_encode("{$this->clientId}:{$this->clientSecret}"),
            "Content-Type: application/x-www-form-urlencoded",
        ];
        $data = "grant_type=client_credentials";

        $ch = curl_init($this->tokenUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 10, // защита от зависаний
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Проверка на сетевую ошибку
        if ($response === false) {
            throw new \Exception("CURL Error while requesting token: $curlError");
        }

        $parsed = json_decode($response, true);

        // Проверка на ошибку декодирования JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("JSON Decode Error: " . json_last_error_msg());
        }

        // Проверка на статус код
        if ($httpCode !== 200 || empty($parsed['access_token'])) {
            $error = $parsed['error_description'] ?? $parsed['error'] ?? 'Unknown error';
            throw new \Exception("Spotify token request failed: HTTP $httpCode, error: $error");
        }

        // Возвращаем access_token или null, если его нет
        return $parsed['access_token'] ?? null;
    }
}
