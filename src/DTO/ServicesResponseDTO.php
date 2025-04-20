<?php

namespace App\DTO;

class ServicesResponseDTO {
    function __construct(
        public mixed $result, 
        public int $code, 
        public ?string $error = null
    ) {}
}