<?php

namespace App\UseCases\SC\Track;

use App\Contracts\BaseContract;
use App\DI\SCServicesDI;

class GetByName extends BaseContract 
{
    private SCServicesDI $di;
    
    public function __construct()
    {
        $this->di = new SCServicesDI();
    }
    
    public function execute(string $name, ?string $primary_artist_name = null)
    {
        $service_request = $this->di->build($this->di::SERVICE_SEARCH);
        if ($service_request->code !== 200) {
            return $service_request;
        }
        
        $query = $primary_artist_name ? "$name - $primary_artist_name" : $name;
        $service_request = $service_request->result->search($query, []);
        
        if ($service_request->code !== 200) {
            return $service_request;
        }
        $best_match = $this->findBestMatch($service_request->result, $name, $primary_artist_name);
        if ($best_match === null) { 
            return parent::response(null, self::HTTP_NOT_FOUND, 'Track from SoundCloud not found'); 
        }
        return parent::response($best_match);
    }

    private function findBestMatch(array $tracks, string $trackName, ?string $primaryArtistName = null): ?object
    {
        $normalizedTrackName = $this->normalizeString($trackName);
        $normalizedArtist = $primaryArtistName ? $this->normalizeString($primaryArtistName) : null;

        $bestMatch = null;
        $maxScore = 0;

        foreach ($tracks as $track) {
            $currentScore = $this->calculateTrackScore($track, $normalizedTrackName, $normalizedArtist);
            
            if ($currentScore > $maxScore) {
                $maxScore = $currentScore;
                $bestMatch = $track;
                
                if ($maxScore >= 90) {
                    break;
                }
            }
        }

        return $bestMatch ?: null;
    }

    protected function calculateTrackScore(object $track, string $searchTrackName, ?string $searchArtistName): int
    {
        $score = 0;
        $trackTitle = $this->normalizeString($track->title);

        // Compare track name
        if ($trackTitle === $searchTrackName) {
            $score += 50;
        } elseif (str_contains($trackTitle, $searchTrackName)) {
            $score += 30;
        }

        // Compare artist if provided
        if ($searchArtistName !== null) {
            $trackArtist = $this->getTrackArtistName($track);
            
            if ($trackArtist === $searchArtistName) {
                $score += 40;
            } elseif (str_contains($trackArtist, $searchArtistName)) {
                $score += 20;
            }
        }

        // Add additional factors
        $score += $this->calculateAdditionalFactors($track);

        return $score;
    }

    protected function getTrackArtistName(object $track): string
    {
        if (isset($track->publisher_metadata['artist'])) {
            return $this->normalizeString($track->publisher_metadata['artist']);
        }
        
        return isset($track->user['username']) 
            ? $this->normalizeString($track->user['username']) 
            : '';
    }

    protected function calculateAdditionalFactors(object $track): int
    {
        $additionalScore = 0;

        // Fixed: properly convert playback count to score
        $popularityScore = (int) min(10, $track->playback_count / 1000000);
        $additionalScore += $popularityScore;

        if (isset($track->user['verified']) && $track->user['verified']) {
            $additionalScore += 5;
        }

        if (!preg_match('/remix|cover|version/i', $track->title)) {
            $additionalScore += 3;
        }

        return $additionalScore;
    }

    protected function normalizeString(string $str): string
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/\([^)]*\)|\[[^]]*\]|[^\w\s]/u', '', $str);
        return trim($str);
    }
}