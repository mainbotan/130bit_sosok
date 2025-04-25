<?php
namespace App\DTO;

use InvalidArgumentException;
use Exception;

class TrackSCDTO
{
    public string $sc_id;
    public string $uri;
    public string $title;
    public ?string $full_name;
    public ?string $image;
    public ?string $description;
    public ?string $release_date;
    public ?string $permalink_url;
    public int $playback_count;
    public int $likes_count;
    public int $reposts_count;
    public int $comment_count;
    public ?string $genre;
    public int $duration;
    public string|array|null $user;
    public string|array|null $publisher_metadata;
    public string|array|null $media;

    public function __construct(array $data, bool $encode = true)
    {
        // Required fields
        $this->sc_id = (string)($data['id'] ?? throw new InvalidArgumentException('Missing track ID'));
        $this->uri = $data['uri'] ?? "soundcloud:track:{$this->id}";
        $this->title = $data['title'] ?? 'Untitled Track';
        
        // Optional fields
        $this->full_name = $data['title'] ?? null;
        $this->image = $data['artwork_url'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->permalink_url = $data['permalink_url'] ?? null;
        $this->playback_count = $data['playback_count'] ?? 0;
        $this->likes_count = $data['likes_count'] ?? 0;
        $this->reposts_count = $data['reposts_count'] ?? 0;
        $this->comment_count = $data['comment_count'] ?? 0;
        $this->genre = $data['genre'] ?? null;
        $this->duration = $data['duration'] ?? 0;

        // Parse release date
        $this->release_date = $this->parseReleaseDate(
            $data['release_date'] ?? 
            $data['display_date'] ?? 
            $data['created_at'] ?? 
            null
        );

        // Process complex fields with encoding flag
        $this->user = $this->safeEncode($data['user'] ?? null, $encode);
        $this->publisher_metadata = $this->safeEncode($data['publisher_metadata'] ?? null, $encode);
        $this->media = $this->safeEncode($data['media'] ?? null, $encode);
    }

    protected function parseReleaseDate(?string $date): ?string
    {
        if (!$date) {
            return null;
        }
        
        try {
            $dt = new \DateTime($date);
            return $dt->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }

    protected function safeEncode(mixed $data, bool $encode): string|array|null
    {
        if ($data === null) {
            return null;
        }

        if (!$encode) {
            return $data;
        }

        try {
            return json_encode($data, JSON_UNESCAPED_UNICODE) ?: 'null';
        } catch (Exception) {
            return 'null';
        }
    }
}