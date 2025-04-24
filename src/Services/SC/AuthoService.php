<?php

namespace App\Services\SC;

use Exception;
use App\Api\SC\Router as SCRouter;
use App\Core\RedisCache;
use App\Services\LoggerService as LoggerService;
use App\Services\ProxyService as ProxyService;
use App\config\proxy as ProxyConfig;
use App\Contracts\BaseContract;

class AuthoService extends BaseContract {
    public function getRouter() {
        try {
            $proxy_config = new ProxyConfig();
            $proxy_config = array_merge(
                $proxy_config->get(),
                ['token' => $_ENV['PROXY_TOKEN'] ?? null,
                'url' => $_ENV['PROXY_URL'] ?? null]
            );
            $proxy_service = new ProxyService($proxy_config);

            $sc_router = new SCRouter(
                new RedisCache(),
                $_ENV['SC_ACCESS_TOKEN'],
                $_ENV['SC_CLIENT_ID'],
                $_ENV['SC_API_URL'],
                $proxy_service,
                new LoggerService()
            );
            return parent::response(
                $sc_router
            );

        } catch (Exception $e) {
            return parent::response(null, self::HTTP_FAIL_AUTH, "SoundCloud Auth failed");
        }
    }
}