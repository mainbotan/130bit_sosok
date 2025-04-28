<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\MainController;
use App\Contracts\BaseResponder;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Получаем тело запроса
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

// Создаем базовый контракт для ответов
$base = new BaseResponder();

// Если невалидный JSON
if (!is_array($data)) {
    $response = $base->makeResponse(
        null,
        BaseResponder::HTTP_BAD_REQUEST,
        'Invalid JSON'
    );
    sendResponse($response);
}

// Достаем endpoint и options
$endpoint = $data['endpoint'] ?? null;
$options = $data['data'] ?? [];

if (!$endpoint) {
    $response = $base->makeResponse(
        null,
        BaseResponder::HTTP_BAD_REQUEST,
        'No endpoint provided'
    );
    sendResponse($response);
}


// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Вызываем главный контроллер
$controller = new MainController();
$response = $controller->execute($endpoint, $options);

// Отправляем ответ
sendResponse($response);

/**
 * Отправка ответа
 */
function sendResponse(\App\DTO\BaseContractResponseDTO $response): void
{
    http_response_code($response->code ?? 500);
    echo json_encode([
        'code' => $response->code ?? 500,
        'result' => $response->result ?? null,
        'error' => $response->error ?? null,
        'metrics' => $response->metrics ?? null,
    ]);
    exit;
}
