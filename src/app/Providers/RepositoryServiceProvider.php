<?php

namespace App\Providers;

use App\Repositories\BoletoRepository;
use App\Repositories\Contracts\BoletoRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BoletoRepositoryInterface::class, BoletoRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
