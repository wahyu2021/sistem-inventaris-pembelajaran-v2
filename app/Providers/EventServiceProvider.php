<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Import model dan observer Anda di sini
use App\Models\Item;
use App\Observers\ItemObserver;
use App\Models\DamageReport;
use App\Observers\DamageReportObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Daftarkan observer Anda di sini
        Item::observe(ItemObserver::class);
        DamageReport::observe(DamageReportObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     * (Metode ini ada di Laravel versi lebih baru)
     */
    public function shouldDiscoverEvents(): bool
    {
        return false; // atau true jika Anda menggunakan event discovery
    }
}