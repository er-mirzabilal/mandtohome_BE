<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class RestAPiServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutes();
    }

    public function loadRoutes(): void
    {
        echo "inside service provider";
        $this->loadRoutesFrom(__DIR__ . '/../Rest/Routes.php');
    }
}
