<?php

namespace App\Factories;

use App\DTO\TrackSCDTO;

class SCDTOFactory
{
    // Одиночные объекты
    public static function track(array $data, $encode=true): TrackSCDTO
    {
        return new TrackSCDTO($data, $encode);
    }


    // Коллекции
    public static function tracks(array $items, $encode=true): array
    {
        return array_map(
            fn(array $item) => new TrackSCDTO($item, $encode),
            $items
        );
    }
}