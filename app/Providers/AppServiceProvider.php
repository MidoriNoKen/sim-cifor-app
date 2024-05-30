<?php

namespace App\Providers;

use App\Interfaces\LeaveApplicationInterface;
use App\Interfaces\RoleInterface;
use App\Interfaces\TravelAuthorisationInterface;
use App\Interfaces\UserInterface;
use App\Repositories\LeaveApplicationRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TravelAuthorisationRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(LeaveApplicationInterface::class, LeaveApplicationRepository::class);
        $this->app->bind(TravelAuthorisationInterface::class, TravelAuthorisationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
