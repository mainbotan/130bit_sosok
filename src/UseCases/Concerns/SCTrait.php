<?php

namespace App\UseCases\Concerns;

use App\DI\SCServicesDI;
use App\Services\MetricsService;

trait SCTrait
{
    protected SCServicesDI $di;
    protected MetricsService $metrics;

    public function initSpotifyServices(bool $storage_metric = false, float $costMultiplier = 20): void
    {
        $this->di = new SCServicesDI();
        $this->metrics = new MetricsService($costMultiplier, $storage_metric);
    }
}