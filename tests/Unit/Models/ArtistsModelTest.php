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

use App\Repositories\ArtistRepository as ArtistRepository;
use App\DTO\ArtistCreateDTO as ArtistCreateDTO;
use App\Models\Artist as ArtistModel;
$artist_repository = new ArtistRepository($pdo);

// // Инициализация
// $result = $artist_repository->searchByName('Carson', 20, 0);

// // Вывод результата
// var_dump($result);

