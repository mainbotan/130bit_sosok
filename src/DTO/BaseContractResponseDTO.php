<?php

namespace App\DTO;

class BaseContractResponseDTO {
    function __construct(
        public mixed $result, 
        public int $code, 
        public ?string $error = null
    ) {}
}