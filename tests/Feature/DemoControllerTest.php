<?php

namespace Tests\Feature;

use App\Events\AppOpened;
use App\Services\DemoAppLauncher;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\TestCase;

class DemoControllerTest extends TestCase
{
    public function test_demo_application_launch_is_disabled_by_default(): void
    {
        config(['app.demo_apps' => false]);
        Event::fake([AppOpened::class]);

        $launcher = $this->mock(DemoAppLauncher::class);
        $launcher->shouldNotReceive('launch');

        $this->postJson('/demo/abrir/calculadora')->assertForbidden();

        Event::assertNotDispatched(AppOpened::class);
    }

    public function test_only_allowlisted_apps_launch_and_broadcast_activity(): void
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            $this->markTestSkipped('La demostración solo está disponible en Windows.');
        }

        config(['app.demo_apps' => true]);
        Event::fake([AppOpened::class]);

        $launcher = $this->mock(DemoAppLauncher::class, function (MockInterface $mock): void {
            $mock->shouldReceive('launch')->twice()->with('calc.exe');
        });

        $this->postJson('/demo/abrir/calculadora')->assertOk()->assertExactJson(['ok' => true]);
        $this->postJson('/demo/abrir/calculadora')->assertOk()->assertExactJson(['ok' => true]);
        $this->postJson('/demo/abrir/calculadora')->assertTooManyRequests();

        Event::assertDispatchedTimes(AppOpened::class, 2);
        Event::assertDispatched(AppOpened::class, function (AppOpened $event): bool {
            return $event->app === 'calculadora'
                && $event->ip === '127.0.0.1'
                && $event->broadcastOn()->name === 'server.demo';
        });
    }

    public function test_demo_route_returns_not_found_for_an_unlisted_app(): void
    {
        $this->postJson('/demo/abrir/command')->assertNotFound();
    }
}
