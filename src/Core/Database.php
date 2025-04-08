<?php
namespace App\Core;

use PDO;
use RuntimeException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';
            $mysql = $config['mysql'];

            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $mysql['host'],
                $mysql['port'],
                $mysql['dbname'],
                $mysql['charset']
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $mysql['user'],
                    $mysql['password'],
                    $mysql['options']
                );
                
                // Тестовый запрос для проверки
                self::$instance->query("SELECT 1")->fetch();
            } catch (\PDOException $e) {
                throw new \RuntimeException(sprintf(
                    "Failed to connect to MySQL: %s\nHost: %s\nPort: %d\nCheck your docker-compose networks",
                    $e->getMessage(),
                    $mysql['host'],
                    $mysql['port']
                ));
            }
        }

        return self::$instance;
    }
}