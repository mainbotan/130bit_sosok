<?
require(__DIR__.'/../../../vendor/autoload.php');

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

// Подключаем кэш
use App\Core\RedisCache;
$redis_cache = new RedisCache();

echo "<pre>";

// Апи авторизации
use App\Api\Spotify\Autho as SpotifyAuthoApi;
$spotify_config = [
    'client_id' => $_ENV['SPOTIFY_CLIENT_ID'],
    'client_secret' => $_ENV['SPOTIFY_CLIENT_SECRET'],
    'api_url' => $_ENV['SPOTIFY_API_URL'],
    'token_url' => $_ENV['SPOTIFY_TOKEN_URL']
];
$spotify_autho_api = new SpotifyAuthoApi($redis_cache, $spotify_config);

// Запрос за токеном
try {
    $access_token = $spotify_autho_api->getAccessToken();
} catch (Exception $e) {
    var_dump(($e->getMessage()));
}

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


// Запрос к апи с использованием прокси
use App\Api\Spotify\Albums as SpotifyAlbumApi;
$spotify_album_api = new SpotifyAlbumApi(
    $redis_cache,
    $access_token,
    $spotify_config['api_url'],
    $proxy_service
);
$result = $spotify_album_api->getAlbum([
    'id' => '0fSfkmx0tdPqFYkJuNX74a'
]);
var_dump($result);