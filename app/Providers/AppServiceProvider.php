<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CreditPackage\CreditPackageRepositoryInterface;
use App\Repositories\CreditPackage\CreditPackageRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->bind(CreditPackageRepositoryInterface::class, CreditPackageRepository::class);
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
