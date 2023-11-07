<?php

namespace App\Listeners;

use App\Events\ManagingOrganizationCreatead;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateGetherer
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Handle the event.
     *
     * @param  \App\Events\ManagingOrganizationCreatead  $event
     * @return void
     */
    public function handle(ManagingOrganizationCreatead $event)
    {
        try {
            $event->organization->update([
                'getherer_id' => Str::uuid()->toString(),
            ]);
        } catch (Exception $ex) {
            Log::error($ex);
            throw $ex;
        }
    }
}
