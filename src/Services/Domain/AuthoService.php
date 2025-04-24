<?php

namespace App\Services\Domain;

use App\Services\BaseService as BaseService;
use App\Core\Database as Database;

class AuthoService extends BaseService{
    public function getPDO () {
        try {
            $pdo = Database::getInstance();
            return parent::response($pdo);
        } catch (\Throwable $th) {
            return parent::response(
                null, self::HTTP_ERROR, $th
            );
        }
    }
}