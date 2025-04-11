<?
require(__DIR__.'/../../../vendor/autoload.php');

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();


// Подключение к бд
use App\Core\Database as Database;
$result = Database::getInstance();

