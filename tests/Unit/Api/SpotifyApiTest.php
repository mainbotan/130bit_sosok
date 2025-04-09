<?
require(__DIR__.'/../../../vendor/autoload.php');

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

// Составляем конфиг
$spotify_config = [
    'client_id' => $_ENV['SPOTIFY_CLIENT_ID'],
    'client_secret' => $_ENV['SPOTIFY_CLIENT_SECRET'],
    'api_url' => $_ENV['SPOTIFY_API_URL'],
    'token_url' => $_ENV['SPOTIFY_TOKEN_URL']
];

// Тестируемое апи
use App\Api\Spotify\Autho as SpotifyAuthoApi;
$spotify_autho_api = new SpotifyAuthoApi($spotify_config);

// Запрос за токеном
try {
    $access_token = $spotify_autho_api->getAccessToken();
} catch (Exception $e) {
    var_dump(($e->getMessage()));
}
var_dump($access_token);

// Тестируемое апи
use App\Api\Spotify\Album as SpotifyAlbumApi;
$spotify_album_api = new SpotifyAlbumApi($access_token, $spotify_config['api_url']);
$result = $spotify_album_api->getAlbum([
    'id' => '0fSfkmx0tdPqFYkJuNX74a'
]);
var_dump($result);
