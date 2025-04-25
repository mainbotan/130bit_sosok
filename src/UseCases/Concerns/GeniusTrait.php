<?php

namespace App\UseCases\Concerns;

use App\DI\GeniusServicesDI;
use App\Services\MetricsService;

trait GeniusTrait
{
    protected GeniusServicesDI $di;
    protected MetricsService $metrics;

    public function initSpotifyServices(bool $storage_metric = false, float $costMultiplier = 20): void
    {
        $this->di = new GeniusServicesDI();
        $this->metrics = new MetricsService($costMultiplier, $storage_metric);
    }
}