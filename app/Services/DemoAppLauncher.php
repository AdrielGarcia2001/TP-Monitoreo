<?php

namespace App\Services;

use RuntimeException;

class DemoAppLauncher
{
    public function launch(string $executable): void
    {
        $process = popen('start "" '.escapeshellarg($executable), 'r');

        if (! is_resource($process)) {
            throw new RuntimeException('No se pudo iniciar el programa solicitado.');
        }

        if (pclose($process) !== 0) {
            throw new RuntimeException('El sistema operativo no pudo iniciar el programa solicitado.');
        }
    }
}
