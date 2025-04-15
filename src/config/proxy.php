<?php
namespace App\config;

class Proxy{
    /**
     * Настройки проксирования
     */
    public function get(): array{
        return [
            'isProxy' => false
        ];
    }
}