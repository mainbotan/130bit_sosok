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

use App\Repositories\PlaylistRepository as PlaylistRepository;
use App\DTO\PlaylistCreateDTO as PlaylistCreateDTO;
use App\Models\Playlist as PlaylistModel;
$playlist_repository = new PlaylistRepository($pdo);

// Инициализация
$result = $playlist_repository->searchByName('грусть', 20, 0);

// Вывод результата
var_dump($result);

