<?php
namespace App\Services;

class LoggerService {
    private string $log_path;

    public function __construct(string $log_path = null) {
        $this->log_path = $log_path ?? (__DIR__ . '/../logs');

        if (!is_dir($this->log_path)) {
            mkdir($this->log_path, 0777, true);
        }
    }

    public function log(string $message, string $level = 'info'): void {
        $date = date('Y-m-d H:i:s');
        $log_line = "[{$date}] {$level}: {$message}" . PHP_EOL;
        $filename = $this->log_path . '/' . date('Y-m-d') . '.log';

        // безопасная запись с блокировкой
        file_put_contents($filename, $log_line, FILE_APPEND | LOCK_EX);
    }

    public function error(string $message): void {
        $this->log($message, 'error');
    }

    public function warning(string $message): void {
        $this->log($message, 'warning');
    }

    public function debug(string $message): void {
        $this->log($message, 'debug');
    }

    public function info(string $message): void {
        $this->log($message, 'info');
    }
}
