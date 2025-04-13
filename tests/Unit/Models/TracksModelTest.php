<?
require(__DIR__.'/../../../vendor/autoload.php');

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();


// Подключение к бд
use App\Core\Database as Database;
$pdo = Database::getInstance();


// AlbumRepository
echo "<pre>";

use App\Repositories\TrackRepository as TrackRepository;
use App\DTO\TrackCreateDTO as TrackCreateDTO;
use App\Models\Track as TrackModel;
$track_repository = new TrackRepository($pdo);

// Инициализация
$result = $track_repository->getAll(100, 0);

// Вывод результата
var_dump($result);

