<?php

namespace App\UseCases\Concerns;

use App\DI\DomainServicesDI;
use App\DI\GeniusServicesDI;
use App\DI\SCServicesDI;
use App\DI\SpotifyServicesDI;
use App\Services\MetricsService;

trait CompositeTrait
{
    protected SCServicesDI $sc_di;
    protected GeniusServicesDI $genius_di;
    protected SpotifyServicesDI $spotify_di;
    protected DomainServicesDI $domain_di;
    protected MetricsService $metrics;

    public function initServices(bool $storage_metric = false, float $costMultiplier = 20): void
    {
        $this->sc_di = new SCServicesDI();
        $this->genius_di = new GeniusServicesDI();
        $this->domain_di = new DomainServicesDI();
        $this->spotify_di = new SpotifyServicesDI();
        $this->metrics = new MetricsService($costMultiplier, $storage_metric);
    }
}