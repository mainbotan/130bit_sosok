<?
require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

// Подключаем кэш
use App\Core\RedisCache;
$redis_cache = new RedisCache();

// Сервис прокси
use App\Services\ProxyService as ProxyService;
use App\config\proxy as ProxyConfig;
$proxy_config = new ProxyConfig();
$proxy_config = array_merge(
    $proxy_config->get(),
    ['token' => $_ENV['PROXY_TOKEN'] ?? null,
    'url' => $_ENV['PROXY_URL'] ?? null]
);
$proxy_service = new ProxyService($proxy_config);

// Объявляем роутер
use App\Services\LoggerService as LoggerService;
$logger = new LoggerService();
use App\Api\Genius\Router as GeniusRouter;
$genius_router = new GeniusRouter(
    $redis_cache,
    $_ENV['GENIUS_ACCESS_TOKEN'],
    $_ENV['GENIUS_API_URL'],
    $proxy_service,
    $logger
);

// Тестируем
use App\Api\Genius\Search as GeniusSearch;
use App\Api\Genius\Songs as GeniusSongs;
use App\Api\Genius\Artists as GeniusArtists;
$genius_search = new GeniusSearch($genius_router);
$genius_songs = new GeniusSongs($genius_router);
$genius_artists = new GeniusArtists($genius_router);

$result = $genius_search->search(
    "We still don't trust you"
);
$result = $genius_artists->getArtistSongs('44080');


var_dump($result);