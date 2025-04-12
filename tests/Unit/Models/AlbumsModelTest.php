<?
require(__DIR__.'/../../../vendor/autoload.php');

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();


// Подключение к бд
use App\Core\Database as Database;
$pdo = Database::getInstance();


// AlbumRepository

use App\Repositories\AlbumRepository as AlbumRepository;
use App\DTO\AlbumCreateDTO as AlbumCreateDTO;
$album_repository = new AlbumRepository($pdo);
$dto = new AlbumCreateDTO([
    'id' => '213123123',
    'name' => 'ХУИЛА',
    'uri' => 'asdasd:213123:asdasd',
    'artists' => [1, 2, 3]
]);
$result = $album_repository->create($dto);
var_dump($result);