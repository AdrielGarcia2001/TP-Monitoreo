<?php

namespace Tests\Feature;

use App\Events\MetricsUpdated;
use App\Services\ServerMetrics;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\TestCase;

class BroadcastMetricsTest extends TestCase
{
    public function test_metrics_command_broadcasts_the_current_snapshot(): void
    {
        $snapshot = [
            'cpu_pct' => 42,
            'at' => '2026-11-05T12:00:00+00:00',
        ];

        $this->mock(ServerMetrics::class, function (MockInterface $mock) use ($snapshot): void {
            $mock->shouldReceive('snapshot')->once()->andReturn($snapshot);
        });
        Event::fake([MetricsUpdated::class]);

        $this->artisan('metrics:broadcast')->assertSuccessful();

        Event::assertDispatched(MetricsUpdated::class, function (MetricsUpdated $event) use ($snapshot): bool {
            return $event->data === $snapshot
                && $event->broadcastOn()->name === 'server.metrics';
        });
    }
}
