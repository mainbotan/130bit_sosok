<?php

namespace App\Factories;

use App\DTO\SC\Track\Get as TrackGet;

class SCDTOFactory
{
    // Одиночные объекты
    public static function track(array $data): TrackGet
    {
        return new TrackGet($data);
    }


    // Коллекции
    public static function tracks(array $items): array
    {
        return array_map(
            fn(array $item) => new TrackGet($item),
            $items
        );
    }
}