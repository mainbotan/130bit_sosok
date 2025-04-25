<?php

namespace App\UseCases\Concerns;

use App\DI\SpotifyServicesDI;
use App\Services\MetricsService;

trait SpotifyTrait
{
    protected SpotifyServicesDI $di;
    protected MetricsService $metrics;

    public function initSpotifyServices(bool $storage_metric = false, float $costMultiplier = 20): void
    {
        $this->di = new SpotifyServicesDI();
        $this->metrics = new MetricsService($costMultiplier, $storage_metric);
    }
}