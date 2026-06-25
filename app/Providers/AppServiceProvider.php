<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Implementations\EloquentUserRepository;
use App\Repositories\Interfaces\TableRepositoryInterface;
use App\Repositories\Implementations\EloquentTableRepository;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Implementations\EloquentReservationRepository;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Implementations\EloquentPaymentRepository;
use App\Repositories\Interfaces\ReservationLogRepositoryInterface;
use App\Repositories\Implementations\EloquentReservationLogRepository;
use App\Factories\ReservationFactory;
use App\Factories\BilliardReservationFactory;
use App\Models\Reservation;
use App\Observers\ReservationObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces to Implementations
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(TableRepositoryInterface::class, EloquentTableRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, EloquentReservationRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, EloquentPaymentRepository::class);
        $this->app->bind(ReservationLogRepositoryInterface::class, EloquentReservationLogRepository::class);

        // Bind Factory
        $this->app->bind(ReservationFactory::class, BilliardReservationFactory::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Observers (Observer Pattern)
        Reservation::observe(ReservationObserver::class);
    }
}
