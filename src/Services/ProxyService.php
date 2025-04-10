<?php
namespace App\Services;

class ProxyService {
    public function __construct(
        private array $proxy_config
    ) {}

    /**
     * Составляет ссылку с токеном для запроса к прокси/нет
     */
    public function getUrl(string $query_url, array $query_headers = []): string {
        if ($this->proxy_config['isProxy'] === true) {
            // Кодируем заголовки
            $encoded_headers = urlencode(implode('|', $query_headers));

            // Кодируем сам URL запроса (включая параметры запроса)
            $encoded_url = urlencode($query_url);

            // Формируем URL для прокси
            $proxy_url = "{$this->proxy_config['url']}?token={$this->proxy_config['token']}&url={$encoded_url}&request_headers={$encoded_headers}";

            return $proxy_url;
        }

        return $query_url;
    }
}
