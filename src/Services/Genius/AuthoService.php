<?php

namespace App\Services\Genius;

use Exception;
use App\Services\BaseService as BaseService;
use App\Api\Genius\Router as GeniusRouter;
use App\Core\RedisCache;
use App\Services\LoggerService as LoggerService;
use App\Services\ProxyService as ProxyService;
use App\config\proxy as ProxyConfig;

class AuthoService extends BaseService {
    public function getRouter() {
        try {
            $proxy_config = new ProxyConfig();
            $proxy_config = array_merge(
                $proxy_config->get(),
                ['token' => $_ENV['PROXY_TOKEN'] ?? null,
                'url' => $_ENV['PROXY_URL'] ?? null]
            );
            $proxy_service = new ProxyService($proxy_config);

            $genius_router = new GeniusRouter(
                new RedisCache(),
                $_ENV['GENIUS_ACCESS_TOKEN'],
                $_ENV['GENIUS_API_URL'],
                $proxy_service,
                new LoggerService()
            );

            return parent::response(
                $genius_router
            );

        } catch (Exception $e) {
            return parent::response(null, self::HTTP_FAIL_AUTH, "Genius Auth failed");
        }
    }
}