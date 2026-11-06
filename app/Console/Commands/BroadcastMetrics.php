<?php

namespace App\Console\Commands;

use App\Events\MetricsUpdated;
use App\Services\ServerMetrics;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('metrics:broadcast')]
#[Description('Lee las métricas del servidor y las envía por WebSocket')]
class BroadcastMetrics extends Command
{
    public function handle(ServerMetrics $metrics): int
    {
        MetricsUpdated::dispatch($metrics->snapshot());

        $this->info('Métricas enviadas a las '.now()->format('H:i:s'));

        return self::SUCCESS;
    }
}
