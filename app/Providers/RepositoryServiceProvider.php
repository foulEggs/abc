<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\PostRepository::class, \App\Repositories\PostRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\OrderRepository::class, \App\Repositories\OrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\OrderSerialRepository::class, \App\Repositories\OrderSerialRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\DistrictRepository::class, \App\Repositories\DistrictRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ClientRepository::class, \App\Repositories\ClientRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\AcceptChannelRepository::class, \App\Repositories\AcceptChannelRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PaymentChannelRepository::class, \App\Repositories\PaymentChannelRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ChargeStaffRepository::class, \App\Repositories\ChargeStaffRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\TerminalRepository::class, \App\Repositories\TerminalRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SysParamRepository::class, \App\Repositories\SysParamRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ClearingRepository::class, \App\Repositories\ClearingRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ClearingRelateRepository::class, \App\Repositories\ClearingRelateRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ClearRelateRepository::class, \App\Repositories\ClearRelateRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\AccountDetailRepository::class, \App\Repositories\AccountDetailRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\AccountCheckRepository::class, \App\Repositories\AccountCheckRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\MenusRepository::class, \App\Repositories\MenusRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\MenuRepository::class, \App\Repositories\MenuRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UriRepository::class, \App\Repositories\UriRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ModelRepository::class, \App\Repositories\ModelRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\RepositoryRepository::class, \App\Repositories\RepositoryRepositoryEloquent::class);
        //:end-bindings:
    }
}
