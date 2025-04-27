<?php

namespace App\DTO\Concerns;

// Genegated by CG

abstract class BaseSerializer
{
    protected function safeJson(?array $data): ?string
    {
        if (empty($data)) {
            return null;
        }

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $json;
    }

    protected function mapArraySerialize(?array $items, string $serializerClass): ?string
    {
        if (empty($items)) {
            return null;
        }

        $serialized = array_map(
            fn($item) => (new $serializerClass($item))->toArray(),
            $items
        );

        return $this->safeJson($serialized);
    }

    /**
     * Сериализация одного вложенного объекта в JSON.
     *
     * @param object|null $item
     * @param string $serializerClass
     * @return string|null
     */
    protected function serializeSingle(?object $item, string $serializerClass): ?string
    {
        if ($item === null) {
            return null;
        }

        $serialized = (new $serializerClass($item))->toArray();

        return $this->safeJson($serialized);
    }
}
