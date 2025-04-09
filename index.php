<?
require(__DIR__.'/vendor/autoload.php');

// Подключение к бд
use App\Core\Database as Database;

// Принимаем данные
$requestBody = file_get_contents('php://input');

// Формат ответа
header('Content-Type: application/json');

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


class Router{
    private $DBH;
    private $requestMethod;
    private $requestBody;
    private $requestAction;
    private $requestOptions;
    public function __construct(
        $requestMethod,
        $requestBody,
        $DBH
    )
    {
        $this->requestMethod = $requestMethod;
        $this->requestBody = $requestBody;
        $this->DBH = $DBH;
    }
    // Обработка запроса
    public function handleRequest(): string {
        // Пробуем декодировать тело
        $input_data = $this->decodeRequest();

        // Перебрасываем на нужный контроллер


        // Здесь нужно как-то определить подключаемый контроллер
        return $this->formatAnswer([
            'status' => 'succes',
            'result' => $input_data 
        ]);
    }
    // Декодирование входных данных
    private function decodeRequest(){
        $parsedBody = json_decode($this->requestBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo $this->formatAnswer([
                'status' => 'error',
                'error' => 'Invalid JSON: ' . json_last_error_msg()
            ]);
            exit;
        }
        // Если получилось декодировать возвращаем action+options
        return [
            'action' => (array) $this->parseAction($parsedBody['action'] ?? ''),
            'options' => (array) $parsedBody['options'] ?? null
        ];
    } 
    // Разбор ендпоинта
    private function parseAction(string $action_string): array {
        $action_parse = explode(':', $action_string);
        return [
            'entity' => (string) $action_parse[0] ?? null,
            'method' => (string) $action_parse[1] ?? null
        ];
    }
    // Формирование ответа
    private function formatAnswer(array $data): string {
        return json_encode([
            'status' => $data['status'] ?? 'unsuccess',
            'result' => $data['result'] ?? null,
            'error' => $data['error'] ?? null
        ], JSON_UNESCAPED_UNICODE);
    }
}

// Инициализируем роутер
$Router = new Router(
    $_SERVER['REQUEST_METHOD'],
    $requestBody,
    Database::getInstance()
);
$resultRouter = $Router->handleRequest();
echo $resultRouter;