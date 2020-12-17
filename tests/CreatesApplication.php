<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Log\Logger;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication() {
        $app = new Application(
            $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
        );

//        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
