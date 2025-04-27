<?php

namespace App\Resolvers\Concerns;

// Контракт
use App\Contracts\BaseContract;
use App\DTO\BaseContractResponseDTO;

abstract class BaseResolver extends BaseContract
{
    const MAX_ROUTES_LEVELS = 8;
    protected array $routes = [];

    private function parseEndpoint(string $endpoint): BaseContractResponseDTO
    {
        $parts = explode('.', $endpoint);

        if (count($parts) > self::MAX_ROUTES_LEVELS) {
            return $this->response(null, self::HTTP_BAD_REQUEST, "The nesting of routes is more than level ". self::MAX_ROUTES_LEVELS);
        }

        $route = $this->routes;

        foreach ($parts as $part) {
            if (!is_array($route) || !isset($route[$part])) {
                return $this->response(null, self::HTTP_NOT_FOUND, "Route part not found: {$part}");
            }

            $route = $route[$part];
        }

        return $this->response($route);
    }

    /**
     * Обработка запроса
     * @param string $enpoint 
     * @param array $options
     * @return BaseContractResponseDTO
     */
    public function execute(string $endpoint, array $options): BaseContractResponseDTO
    {
        $parsedEndpoint = $this->parseEndpoint($endpoint);
        if ($parsedEndpoint->code != self::HTTP_OK) {
            return $parsedEndpoint;
        }

        $routeConfig = $parsedEndpoint->result;
        $middlewares = $routeConfig['middlewares'] ?? [];
        $useCaseClass = $routeConfig['use_case'] ?? null;

        if ($useCaseClass === null || !class_exists($useCaseClass)) {
            return $this->response(null, self::HTTP_ERROR, "UseCase class not found for endpoint: {$endpoint}");
        }

        $useCase = new $useCaseClass(true);

        $dataFromClient = $options;
        $data = $options;

        foreach ($middlewares as $middlewareClass) {
            if (!class_exists($middlewareClass)) {
                return $this->response(null, self::HTTP_ERROR, "Middleware class not found: {$middlewareClass}");
            }

            $middleware = new $middlewareClass();
            $response = $middleware->execute($dataFromClient);

            if ($response->code !== self::HTTP_OK) {
                return $this->response(null, $response->code, $response->error);
            }

            $data = array_merge($data, $response->result ?? []);
        }

        return $useCase->execute($data);
    }
}
