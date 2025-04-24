<?php

namespace App\Contracts;

use App\DTO\BaseContractResponseDTO;

abstract class BaseContract
{
    const HTTP_OK = 200;
    const HTTP_NOT_FOUND = 404;
    const HTTP_ERROR = 500;
    const HTTP_FAIL_AUTH = 401;
    const HTTP_BAD_REQUEST = 400;

    /**
     * Форматирует ответ сервиса.
     * 
     * @param mixed $result Данные или null при ошибке.
     * @param int $code HTTP-код или кастомный статус.
     * @param string|null $error Описание ошибки.
     * @return array Стандартизированный ответ.
     */
    protected function response(
        mixed $result, 
        int $code = self::HTTP_OK, 
        ?string $error = null
    ): BaseContractResponseDTO {
        return new BaseContractResponseDTO(
            $result,
            $code,
            $error
        );
    }
    
    // Можно добавить другие общие методы (логирование, валидацию и т.д.)
}