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
            // Грузим .env если ещё не загружен
            if (!isset($_ENV['POSTGRES_DB'])) {
                $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
                $dotenv->load();
            }

            $config = require __DIR__ . '/../config/database.php';
            $pgsql = $config['pgsql'];

            $dsn = sprintf(
                'pgsql:host=%s;port=%d;dbname=%s',
                $pgsql['host'],
                $pgsql['port'],
                $pgsql['dbname']
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $pgsql['user'],
                    $pgsql['password'],
                    $pgsql['options']
                );

                self::$instance->query("SELECT 1")->fetch();
            } catch (\PDOException $e) {
                throw new \RuntimeException(sprintf(
                    "Failed to connect to PostgreSQL: %s\nHost: %s\nPort: %d",
                    $e->getMessage(),
                    $pgsql['host'],
                    $pgsql['port']
                ));
            }
        }

        return self::$instance;
    }

}
