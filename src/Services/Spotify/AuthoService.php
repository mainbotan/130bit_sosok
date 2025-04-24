<?php

namespace App\Services\Spotify;

use Exception;
use App\Api\Spotify\Router as SpotifyRouter;
use App\Api\Spotify\Autho as SpotifyAuthoApi;
use App\Core\RedisCache;
use App\Services\LoggerService as LoggerService;
use App\Services\ProxyService as ProxyService;
use App\config\proxy as ProxyConfig;
use App\Contracts\BaseContract;

class AuthoService extends BaseContract {
    public function getRouter() {
        // Тягаем конфиг спотика
        $spotify_config = [
            'client_id' => $_ENV['SPOTIFY_CLIENT_ID'],
            'client_secret' => $_ENV['SPOTIFY_CLIENT_SECRET'],
            'api_url' => $_ENV['SPOTIFY_API_URL'],
            'token_url' => $_ENV['SPOTIFY_TOKEN_URL']
        ];
        // Объявляем апиху
        $spotify_autho_api = new SpotifyAuthoApi(
            new RedisCache(),
            $spotify_config
        );


        try {
            $access_token = $spotify_autho_api->getAccessToken();

            $proxy_config = new ProxyConfig();
            $proxy_config = array_merge(
                $proxy_config->get(),
                ['token' => $_ENV['PROXY_TOKEN'] ?? null,
                'url' => $_ENV['PROXY_URL'] ?? null]
            );
            $proxy_service = new ProxyService($proxy_config);

            $spotify_router = new SpotifyRouter(
                new RedisCache,
                $access_token,
                $spotify_config['api_url'],
                $proxy_service,
                new LoggerService()
            );

            return parent::response(
                $spotify_router
            );

        } catch (Exception $e) {
            return parent::response(null, self::HTTP_FAIL_AUTH, "Spotify Auth failed");
        }
    }
}