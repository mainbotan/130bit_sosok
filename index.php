<?
require(__DIR__.'/vendor/autoload.php');

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Подключение к бд
use App\Core\Database as Database;

$system = [
    'root' => __DIR__,
    'env' => $_ENV['MYSQL_USER'],
    'database' => Database::getInstance(),
    'php_version' => phpversion()
];

echo "<pre>";
var_dump($system);
echo "</pre>";
?>