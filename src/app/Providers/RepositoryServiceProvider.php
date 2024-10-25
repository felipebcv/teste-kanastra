<?php

namespace App\Providers;

use App\Repositories\BoletoRepository;
use App\Repositories\Contracts\BoletoRepositoryInterface;
use App\Services\BoletoService;
use App\Services\Contracts\BoletoServiceInterface;
use App\Services\Contracts\CsvProcessorServiceInterface;
use App\Services\CsvProcessorService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BoletoRepositoryInterface::class, BoletoRepository::class);
        $this->app->bind(BoletoServiceInterface::class, BoletoService::class);        
        $this->app->bind(CsvProcessorServiceInterface::class, CsvProcessorService::class);        
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
