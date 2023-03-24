<?php

namespace App\Providers;

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param  BroadcastManager  $broadcastManager
     */
    public function boot(BroadcastManager $broadcastManager): void
    {
        $broadcastManager->routes();

        require base_path('routes/channels.php');
    }
}
