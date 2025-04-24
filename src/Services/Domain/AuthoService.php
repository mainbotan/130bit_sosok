<?php

namespace App\Services\Domain;

use App\Contracts\BaseContract;
use App\Core\Database as Database;

class AuthoService extends BaseContract {
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