<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CreditPackage\CreditPackageRepositoryInterface;
use App\Repositories\CreditPackage\CreditPackageRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->bind(CreditPackageRepositoryInterface::class, CreditPackageRepository::class);
       $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
       $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
