<?php

namespace App\Providers;

use App\Events\InvoiceCreated;
use App\Events\InvoiceFileCreated;
use App\Events\ManagingOrganizationCreatead;
use App\Listeners\GenerateEcoDuty;
use App\Listeners\GenerateGetherer;
use App\Listeners\ValidateInvoice;
use App\Listeners\ValidateInvoiceFile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ManagingOrganizationCreatead::class => [
            GenerateGetherer::class,
            GenerateEcoDuty::class,
        ],
        InvoiceFileCreated::class => [
            ValidateInvoiceFile::class
        ],
        InvoiceCreated::class => [
            ValidateInvoice::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
