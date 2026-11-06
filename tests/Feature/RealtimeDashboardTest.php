<?php

namespace Tests\Feature;

use App\Services\ServerMetrics;
use Mockery\MockInterface;
use Tests\TestCase;

class RealtimeDashboardTest extends TestCase
{
    public function test_server_monitor_uses_websocket_updates_without_polling(): void
    {
        $this->withoutVite();
        $this->mock(ServerMetrics::class, function (MockInterface $mock): void {
            $mock->shouldReceive('snapshot')->once()->andReturn([
                'hostname' => 'test-host',
                'os' => 'Windows',
                'php' => '8.4.24',
                'disk' => ['used_pct' => 42],
                'memory' => ['used_pct' => 50],
                'cpu_pct' => 25,
                'uptime_s' => 100,
                'cpu_temp_c' => null,
                'at' => '2026-11-05T12:00:00+00:00',
            ]);
        });

        $this->get('/servidor')
            ->assertOk()
            ->assertSee('id="estado"', false)
            ->assertSee('id="actividad"', false)
            ->assertSee('Ver gráficos en tiempo real')
            ->assertDontSee('setInterval(');
    }

    public function test_graphs_page_renders_reusable_cpu_memory_and_disk_charts(): void
    {
        $this->withoutVite();

        $this->get('/graficos')
            ->assertOk()
            ->assertSee('data-grafico="cpu"', false)
            ->assertSee('data-grafico="memory"', false)
            ->assertSee('data-grafico="disk"', false)
            ->assertSee('data-tipo="medidor"', false);
    }
}
