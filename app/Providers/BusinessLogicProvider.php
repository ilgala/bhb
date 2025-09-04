<?php

namespace App\Providers;

use App\Services\BookingService;
use App\Services\Contracts\BookingService as BookingServiceContract;
use App\Services\Contracts\GoogleCalendarService as GoogleCalendarServiceContract;
use App\Services\Contracts\UserService as UserServiceContract;
use App\Services\GoogleCalendarService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class BusinessLogicProvider extends ServiceProvider
{
    protected array $services = [
        BookingServiceContract::class => BookingService::class,
        GoogleCalendarServiceContract::class => GoogleCalendarService::class,
        UserServiceContract::class => UserService::class,
    ];

    public function register(): void
    {
        foreach ($this->services as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
