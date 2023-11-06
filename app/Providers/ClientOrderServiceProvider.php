<?php

namespace App\Providers;

use App\Models\ClientOrder;
use App\Repositories\ClientOrderRepository\ClientOrderModelRepository;
use App\Repositories\ClientOrderRepository\ClientOrderRepository;
use Illuminate\Support\ServiceProvider;

class ClientOrderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(ClientOrderRepository::class, function() {
            return new ClientOrderModelRepository();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
