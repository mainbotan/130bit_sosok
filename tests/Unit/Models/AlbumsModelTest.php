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

use App\Repositories\AlbumRepository as AlbumRepository;
use App\DTO\AlbumCreateDTO as AlbumCreateDTO;
use App\Models\Album as AlbumModel;
$album_repository = new AlbumRepository($pdo);

// Инициализация
$result = $album_repository->getById('2QRedhP5RmKJiJ1i8VgDGR');

// $result = $album_repository->getAll(100, 0);

// Вывод результата
var_dump($result);

