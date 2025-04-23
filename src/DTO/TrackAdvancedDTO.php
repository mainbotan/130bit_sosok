<?php
namespace App\DTO;

use Exception;
use InvalidArgumentException;


// Вложенные структуры
use App\DTO\ArtistAdvancedDTODTO;

class TrackAdvancedDTO
{
    public string $genius_id;
    public ?string $genius_url;
    public ?string $genius_lyrics_url;
    public string $uri;
    public string $name;
    public ?string $full_name;
    public ?string $image;
    public ?string $lyrics_owner_id;
    public ?string $release_date;
    public ?int $annotation_count;

    public array|string|null $stats;
    public array|string|null $artists;

    public function __construct(array $data, bool $encode = true)
    {
        // Required fields (throw exception if missing)
        $this->genius_id = $data['id'] ?? throw new InvalidArgumentException('Missing track ID');
        $this->uri = "genius:track:{$this->genius_id}";
        $this->name = $data['title'] ?? 'Untitled Track';

        // Optional fields
        $this->genius_url = $data['relationships_index_url'] ?? null;
        $this->genius_lyrics_url = $data['url'] ?? null;
        $this->full_name = $data['full_title'] ?? null;
        $this->image = $data['header_image_url'] ?? null;
        $this->lyrics_owner_id = $data['lyrics_owner_id'] ?? null;
        $this->annotation_count = $data['annotation_count'] ?? null;

        // Parse release date safely
        $this->release_date = $this->parseReleaseDate($data['release_date_components'] ?? null);

        // Process artists (primary + featured)
        $artists = array_merge(
            $data['primary_artists'] ?? [],
            $data['featured_artists'] ?? []
        );

        // Encode or keep as objects
        $this->stats = $this->safeEncode($data['stats'] ?? [], $encode);
        $this->artists = $encode 
            ? $this->safeEncode($artists, $encode) 
            : $this->mapArtists($artists, $encode);
    }

    protected function parseReleaseDate(?array $components): ?string
    {
        if (!$components) {
            return null;
        }
        return sprintf(
            '%d-%02d-%02d',
            $components['year'] ?? 0,
            $components['month'] ?? 1,
            $components['day'] ?? 1
        );
    }

    protected function mapArtists(array $artists, bool $encode): array
    {
        return array_map(
            fn(array $artist) => new ArtistAdvancedDTO($artist, $encode),
            $artists
        );
    }

    protected function safeEncode(mixed $data, bool $encode): string|array
    {
        if (!$encode) {
            return $data;
        }
        try {
            return json_encode($data, JSON_UNESCAPED_UNICODE) ?: '{}';
        } catch (Exception) {
            return '{}';
        }
    }
}