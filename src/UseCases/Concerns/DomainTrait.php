<?php

namespace App\UseCases\Concerns;

use App\DI\DomainServicesDI;
use App\Services\MetricsService;

trait DomainTrait
{
    protected DomainServicesDI $di;
    protected MetricsService $metrics;

    public function initServices(bool $storage_metric = false, float $costMultiplier = 20): void
    {
        $this->di = new DomainServicesDI();
        $this->metrics = new MetricsService($costMultiplier, $storage_metric);
    }
}