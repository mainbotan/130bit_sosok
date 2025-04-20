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
use App\Api\SC\Router as SCRouter;
$sc_router = new SCRouter(
    $redis_cache,
    $_ENV['SC_ACCESS_TOKEN'],
    $_ENV['SC_CLIENT_ID'],
    $_ENV['SC_API_URL'],
    $proxy_service,
    $logger
);
// Тестируем
use App\Api\SC\Search as SCSearch;
$sc_search = new SCSearch($sc_router);

$result = $sc_search->search(
    "We still don't trust you"
);

var_dump($result);