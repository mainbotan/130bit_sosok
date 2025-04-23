<?php

require(__DIR__.'/../../../vendor/autoload.php');

echo "<pre>";

// Загружаем переменные из .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

use App\Services\SC\AuthoService as SCAuthoService;
use App\Services\SC\SearchService as SCSearchService;
use App\Api\SC\Search as SCSearchApi;

$sc_autho_service = new SCAuthoService();
$sc_autho_answer = $sc_autho_service->getRouter();
if ($sc_autho_answer->code === 200){


    // Тест сервиса поиска

    $sc_search_api = new SCSearchApi($sc_autho_answer->result);
    $sc_search_service = new SCSearchService($sc_search_api);

    $sc_search_answer = $sc_search_service->search (
        'Knife Talk - 21 Savage & Drake', ['limit' => 15]
    );
    var_dump($sc_search_answer);


}
