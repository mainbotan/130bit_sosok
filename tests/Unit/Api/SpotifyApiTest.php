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

// Объявляем роутер
use App\Services\LoggerService as LoggerService;
$logger = new LoggerService();
use App\Api\Spotify\Router as SpotifyRouter;
$spotify_router = new SpotifyRouter(
    $redis_cache,
    $access_token,
    $spotify_config['api_url'],
    $proxy_service,
    $logger
);

// Запрос к апи с использованием прокси
use App\Api\Spotify\Albums as SpotifyAlbumApi;
use App\Api\Spotify\Artists as SpotifyArtistsApi;
use App\Api\Spotify\Tracks as SpotifyTracksApi;
use App\Api\Spotify\Playlists as SpotifyPlaylistsApi;
use App\Api\Spotify\Audiobooks as SpotifyAudiobooksApi;
use App\Api\Spotify\Search as SpotifySearchApi;

$spotify_album_api = new SpotifyAlbumApi($spotify_router);
$spotify_tracks_api = new SpotifyTracksApi($spotify_router);
$spotify_artists_api = new SpotifyArtistsApi($spotify_router);
$spotify_playlists_api = new SpotifyPlaylistsApi($spotify_router);
$spotify_audiobooks_api = new SpotifyAudiobooksApi($spotify_router);
$spotify_search_api = new SpotifySearchApi($spotify_router);

$result = $spotify_artists_api->getArtist([
    'id' => '3gBZUcNeVumkeeJ19CY2sX'
]);
// $result = $spotify_search_api->search([
//     'query' => 'Playboi Carti',
//     'type' => ['artist', 'album'],
//     'offset' => 0,
//     'limit' => 10
// ]);
var_dump($result);

// Подключение к бд
use App\Core\Database as Database;
$pdo = Database::getInstance();

use App\Repositories\ArtistRepository as ArtistRepository;
use App\DTO\ArtistCreateDTO as ArtistCreateDTO;
use App\Models\Artist as ArtistModel;
$artist_repository = new ArtistRepository($pdo);

// // // Инициализация
// $result = $artist_repository->create(new ArtistCreateDTO(
//     $artist
// ));
// var_dump($result);

// use App\Repositories\AlbumRepository as AlbumRepository;
// use App\DTO\AlbumCreateDTO as AlbumCreateDTO;
// $album_repository = new AlbumRepository($pdo);
// $dto = new AlbumCreateDTO($album);

// // Инициализация
// $result = $album_repository->create($dto);
// // Вывод результата
// var_dump($result);


// // Инициализация
// $result = $album_repository->getAll(100, 0);
// // Вывод результата
// var_dump($result);


