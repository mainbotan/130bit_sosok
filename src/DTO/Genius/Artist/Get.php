<?php

namespace App\DTO\Genius\Artist;

use Exception;

class Get
{
    public string $genius_id;
    public ?string $genius_url;
    public string $uri;
    public string $name;
    public string $avatar;
    public ?string $header_image;
    public int $followers;
    public int $iq;
    public bool $is_verified;
    public ?string $description;
    public array|string|null $alternate_names;
    public array|string|null $media;

    public function __construct(array $data)
    {
        // Required fields
        $this->genius_id = (string) $data['id'];
        $this->uri = "genius:artist:{$this->genius_id}";
        $this->name = (string) $data['name'];
        $this->avatar = (string) ($data['image_url'] ?? '');

        // Optional fields with defaults
        $this->genius_url = $data['url'] ?? null;
        $this->header_image = $data['header_image_url'] ?? null;
        $this->followers = (int) ($data['followers_count'] ?? 0);
        $this->iq = (int) ($data['iq'] ?? 0);
        $this->is_verified = (bool) ($data['is_verified'] ?? false);

        // Description handling (multiple possible formats)
        $this->description = $this->extractDescription($data['description'] ?? null);

        // Social media
        $this->media = $this->prepareMediaData($data);

        // Alternate names
        $this->alternate_names = $data['alternate_names'] ?? [];
    }

    protected function extractDescription(?array $description): ?string
    {
        if (empty($description)) {
            return null;
        }

        return $description['plain'] 
            ?? $description['html'] 
            ?? $description['dom'] 
            ?? null;
    }

    protected function prepareMediaData(array $data): array|string
    {
        if (isset($data['facebook_name']) or 
            isset($data['instagram_name']) or 
            isset($data['twitter_name'])
        ) {
            $media = [
                'facebook' => ['name' => $data['facebook_name'] ?? null],
                'instagram' => ['name' => $data['instagram_name'] ?? null],
                'twitter' => ['name' => $data['twitter_name'] ?? null],
            ];
        } else {
            $media = [];
        }
        return $media;
    }
}