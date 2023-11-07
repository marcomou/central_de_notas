<?php

namespace App\Listeners;

use App\Enums\EcoDutyStatus;
use App\Events\ManagingOrganizationCreatead;
use App\Models\EcoRuleset;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GenerateEcoDuty
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ManagingOrganizationCreatead  $event
     * @return void
     */
    public function handle(ManagingOrganizationCreatead $event)
    {
        try {
            $ecoRuleset = EcoRuleset::first();
            $metadata = [
                'url_name' => 'texto qualquer',
                'url_page' => 'uma url qualquer',
                'description' => "",
                'interloctor' => [],
                'system_name' => 'Teste Ruan  0332',
                'operational_data' => [
                    'recycling_credit_system' => true,
                    'support_screening_centers' => true,
                    'recycling_credit_system_residual_percent' => [
                        "plastic" => 100,
                        "paper" => 100,
                        "metal" => 100,
                        "glass" => 100,
                    ],
                ],
                'residual_object_system' => 'Qualquer resíduo',
            ];

            $event->organization->ecoDuties()->create([
                'eco_ruleset_id' => $ecoRuleset->id,
                'status' => EcoDutyStatus::APPROVED,
                'metadata' => $metadata,
                'managing_code' => uniqid(),
            ]);
        } catch (Exception $ex) {
            Log::error($ex);
            throw $ex;
        }
    }
}
