<?php

namespace App\Contracts;

class BaseResponder extends BaseContract
{
    /**
     * Публичный метод для создания ответа
     */
    public function makeResponse(
        mixed $result = null, 
        int $code = self::HTTP_OK, 
        string $error = null
    ): \App\DTO\BaseContractResponseDTO
    {
        return parent::response($result, $code, $error);
    }
}