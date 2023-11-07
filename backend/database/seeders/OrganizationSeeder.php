<?php

namespace Database\Seeders;

use App\Enums\EcoDutyReviewType;
use App\Enums\EcoDutyStatus;
use App\Models\EcoDuty;
use App\Models\EcoDutyReview;
use App\Models\EcoRuleset;
use App\Models\EcoSystem;
use App\Models\LegalType;
use App\Models\LiabilityDeclaration;
use App\Models\Location;
use App\Models\MaterialType;
use App\Models\Organization;
use App\Models\User;
use App\Utils\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $federalOrganizationUsers = $users->filter(function ($value, $key) {

            $users = collect([
                'eduardo.cavalcante@nhecotech.com',
                'eduardo.andrade@nhecotech.com',
                'leonardo@nhecotech.com',
                'elcio@nhecotech.com',
                'jonathan@inundaweb.com.br',
            ]);

            return $users->contains($value->email);
        });

        $federalOrganization = Organization::withoutEvents(function () {
            return Organization::updateOrCreate(
                [
                    'federal_registration' => config('app.federal_registration_mma'),
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'legal_name' => 'Orgão Federal',
                    'front_name' => 'Orgão Federal',
                    'legal_type_id' => LegalType::firstWhere('name', 'Empresa pública')->id,
                    'getherer_id' => null,
                ]
            );
        });

        $supervisingOrganizationOne = Organization::withoutEvents(function () {
            return Organization::create([
                'id' => Str::uuid()->toString(),
                'federal_registration' => Utils::generateCnpj(),
                'legal_name' => 'verificador 1',
                'front_name' => 'verificador 1',
                'legal_type_id' => LegalType::firstWhere('name', 'Empresa pública')->id,
                'getherer_id' => null,
            ]);
        });

        $supervisingOrganizationTwo = Organization::withoutEvents(function () {
            return Organization::create([
                'id' => Str::uuid()->toString(),
                'federal_registration' => Utils::generateCnpj(),
                'legal_name' => 'verificador 2',
                'front_name' => 'verificador 2',
                'legal_type_id' => LegalType::firstWhere('name', 'Empresa pública')->id,
                'getherer_id' => null,
            ]);
        });

        $managingOrganizationOneSupersivingOrganizationOne = Organization::withoutEvents(function () {
            return Organization::create([
                'id' => Str::uuid()->toString(),
                'federal_registration' => Utils::generateCnpj(),
                'legal_name' => 'Entidade gestora 1 - Órgão estuadal 1',
                'front_name' => 'Entidade gestora 1 - Órgão estadual 1',
                'legal_type_id' => LegalType::firstWhere('name', 'Empresa pública')->id,
                'getherer_id' => '000000000000000000000000000000000001',
            ]);
        });

        $managingOrganizationTwoSupervisingOrganizationOne = Organization::withoutEvents(function () {
            return Organization::create([
                'id' => Str::uuid()->toString(),
                'federal_registration' => Utils::generateCnpj(),
                'legal_name' => 'Entidade gestora 2 - Órgão estadual  1',
                'front_name' => 'Entidade gestora 2 - Órgão estadual  1',
                'legal_type_id' => LegalType::firstWhere('name', 'Empresa pública')->id,
                'getherer_id' => '000000000000000000000000000000000001',
            ]);
        });

        $managingOrganizationThreeSupersivingOrganizationOne = Organization::withoutEvents(function () {
            return Organization::create([
                'id' => Str::uuid()->toString(),
                'federal_registration' => Utils::generateCnpj(),
                'legal_name' => 'Entidade gestora 3 - Órgão estadual  1',
                'front_name' => 'Entidade gestora 3 - Órgão estadual  1',
                'legal_type_id' => LegalType::firstWhere('name', 'Empresa pública')->id,
                'getherer_id' => '000000000000000000000000000000000001',
            ]);
        });

        $managingOrganizationOneSupervisingOrganizationTwo = Organization::withoutEvents(function () {
            return Organization::create([
                'id' => Str::uuid()->toString(),
                'federal_registration' => Utils::generateCnpj(),
                'legal_name' => 'Entidade gestora 1 - Órgão estadual  2',
                'front_name' => 'Entidade gestora 1 - Órgão estadual  2',
                'legal_type_id' => LegalType::firstWhere('name', 'Empresa pública')->id,
                'getherer_id' => '000000000000000000000000000000000001',
            ]);
        });

        $managingOrganizationTwoSupervisingOrganizationTwo = Organization::withoutEvents(function () {
            return Organization::create([
                'id' => Str::uuid()->toString(),
                'federal_registration' => Utils::generateCnpj(),
                'legal_name' => 'Entidade gestora 2 - Órgão estadual  2',
                'front_name' => 'Entidade gestora 2 - Órgão estadual  2',
                'legal_type_id' => LegalType::firstWhere('name', 'Empresa pública')->id,
                'getherer_id' => '000000000000000000000000000000000001',
            ]);
        });

        $federalOrganization->users()->sync($federalOrganizationUsers);

        $supervisingOrganizationOne->users()->sync($users);
        $managingOrganizationOneSupersivingOrganizationOne->users()->sync($users);
        $managingOrganizationTwoSupervisingOrganizationOne->users()->sync($users);
        $managingOrganizationThreeSupersivingOrganizationOne->users()->sync($users);

        $supervisingOrganizationTwo->users()->sync($users);
        $managingOrganizationOneSupervisingOrganizationTwo->users()->sync($users);
        $managingOrganizationTwoSupervisingOrganizationTwo->users()->sync($users);

        $locationOne = Location::inRandomOrder()->first()->id;
        $locationTwo = Location::inRandomOrder()->whereNotIn('id', [$locationOne])->first()->id;

        $ecoSystemOne = EcoSystem::create([
            'supervising_organization_id' => $supervisingOrganizationOne->id,
            'name' => uniqid('SigLog_'),
            'location_id' => $locationOne,
        ]);

        $ecoSystemTwo = EcoSystem::create([
            'supervising_organization_id' => $supervisingOrganizationTwo->id,
            'name' => uniqid('SigLog_'),
            'location_id' => $locationTwo,
        ]);

        $dutyYears = [2019, 2020, 2021, 2022];

        foreach ($dutyYears as $dutyYear) {

            $ecoRulesetOne = EcoRuleset::create([
                'eco_system_id' => $ecoSystemOne->id,
                'duty_year' => $dutyYear,
                'rules' => [
                    "goals" => [
                        ["material_code" => "paper", "min_percent" => 22],
                        ["material_code" => "metal", "min_percent" => 22],
                        ["material_code" => "plastic", "min_percent" => 22],
                        ["material_code" => "glass", "min_percent" => 22],
                    ],
                    "process" => [
                        "phases" => [
                            [
                                "title" => "Fase 1",
                                "permit" => [
                                    "duties/*",
                                    "memberships/*",
                                    "documents/*",
                                    "declarations/*",
                                    "invoices/*",
                                ],
                            ],
                            [
                                "title" => "Fase 2",
                                "permit" => [],
                            ],
                            [
                                "title" => "Fase 3",
                                "permit" => [
                                    "duties/edit",
                                    "duties/abandon",
                                    "memberships/operators/edit",
                                    "documents/operators/*",
                                    "declarations/edit",
                                    "invoices/*",
                                ],
                            ],
                            [
                                "title" => "Fase 4",
                                "permit" => [],
                            ],
                        ],
                        "phase_auto_transitions" => [
                            [
                                "when" => "2022-04-13 03:59:59",
                                "from_phase" => "Fase 1",
                                "to_phase" => "Fase 2",
                            ],
                            [
                                "when" => "2022-04-28 03:59:59",
                                "from_phase" => "Fase 2",
                                "to_phase" => "Fase 3",
                            ],
                            [
                                "when" => "2022-05-20 03 =>59 =>59",
                                "from_phase" => "Fase 3",
                                "to_phase" => "Fase 4",
                            ],
                        ],
                    ],
                ],
            ]);

            $ecoRulesetTwo = EcoRuleset::create([
                'eco_system_id' => $ecoSystemTwo->id,
                'duty_year' => $dutyYear,
                'rules' => [
                    "goals" => [
                        ["material_code" => "paper", "min_percent" => 22],
                        ["material_code" => "metal", "min_percent" => 22],
                        ["material_code" => "plastic", "min_percent" => 22],
                        ["material_code" => "glass", "min_percent" => 22],
                    ],
                    "process" => [
                        "phases" => [
                            [
                                "title" => "Fase 1",
                                "permit" => [
                                    "duties/*",
                                    "memberships/*",
                                    "documents/*",
                                    "declarations/*",
                                    "invoices/*",
                                ],
                            ],
                            [
                                "title" => "Fase 2",
                                "permit" => [],
                            ],
                            [
                                "title" => "Fase 3",
                                "permit" => [
                                    "duties/edit",
                                    "duties/abandon",
                                    "memberships/operators/edit",
                                    "documents/operators/*",
                                    "declarations/edit",
                                    "invoices/*",
                                ],
                            ],
                            [
                                "title" => "Fase 4",
                                "permit" => [],
                            ],
                        ],
                        "phase_auto_transitions" => [
                            [
                                "when" => "2022-04-13 03:59:59",
                                "from_phase" => "Fase 1",
                                "to_phase" => "Fase 2",
                            ],
                            [
                                "when" => "2022-04-28 03:59:59",
                                "from_phase" => "Fase 2",
                                "to_phase" => "Fase 3",
                            ],
                            [
                                "when" => "2022-05-20 03 =>59 =>59",
                                "from_phase" => "Fase 3",
                                "to_phase" => "Fase 4",
                            ],
                        ],
                    ],
                ],
            ]);

            $ecoDutyOne = EcoDuty::create([
                'eco_ruleset_id' => $ecoRulesetOne->id,
                'managing_organization_id' => $managingOrganizationOneSupersivingOrganizationOne->id,
                'managing_code' => uniqid('MANAGING_CODE_'),
                'status' => EcoDutyStatus::getRandomValue(),
                'metadata' => [
                    'url_name' => 'texto qualquer',
                    'url_page' => 'uma url qualquer',
                    'description' => 'Alguma descrição desse sistema',
                    'interloctor' => [
                        'name' => 'Nome do interlocutor',
                        'email' => 'interlocutor@email.com',
                        'phone' => '00 0 00000000',
                        'document' => Utils::generateCpf(),
                        'registration_document' => '1234567',
                    ],
                    'system_name' => 'Teste Ruan  0332',
                    'operational_data' => [
                        'recycling_credit_system' => true,
                        'support_screening_centers' => true,
                        'recycling_credit_system_residual_percent' => [
                            'plastic' => 22,
                            'paper' => 22,
                            'glass' => 22,
                            'metal' => 22,
                        ],
                    ],
                    'residual_object_system' => 'Qualquer resíduo',
                ],
            ]);

            $ecoDutyTwo = EcoDuty::create([
                'eco_ruleset_id' => $ecoRulesetOne->id,
                'managing_organization_id' => $managingOrganizationTwoSupervisingOrganizationOne->id,
                'managing_code' => uniqid('MANAGING_CODE_'),
                'status' => EcoDutyStatus::getRandomValue(),
                'metadata' => [
                    'url_name' => 'texto qualquer',
                    'url_page' => 'uma url qualquer',
                    'description' => 'Alguma descrição desse sistema',
                    'interloctor' => [
                        'name' => 'Nome do interlocutor',
                        'email' => 'interlocutor@email.com',
                        'phone' => '00 0 00000000',
                        'document' => Utils::generateCpf(),
                        'registration_document' => '1234567',
                    ],
                    'system_name' => 'Teste Ruan  0332',
                    'operational_data' => [
                        'recycling_credit_system' => true,
                        'support_screening_centers' => true,
                        'recycling_credit_system_residual_percent' => [
                            'paper' => 22,
                            'glass' => 22,
                            'metal' => 22,
                        ],
                    ],
                    'residual_object_system' => 'Qualquer resíduo',
                ],
            ]);

            $ecoDutyThree = EcoDuty::create([
                'eco_ruleset_id' => $ecoRulesetOne->id,
                'managing_organization_id' => $managingOrganizationThreeSupersivingOrganizationOne->id,
                'managing_code' => uniqid('MANAGING_CODE_'),
                'status' => EcoDutyStatus::getRandomValue(),
                'metadata' => [
                    'url_name' => 'texto qualquer',
                    'url_page' => 'uma url qualquer',
                    'description' => 'Alguma descrição desse sistema',
                    'interloctor' => [
                        'name' => 'Nome do interlocutor',
                        'email' => 'interlocutor@email.com',
                        'phone' => '00 0 00000000',
                        'document' => Utils::generateCpf(),
                        'registration_document' => '1234567',
                    ],
                    'system_name' => 'Teste Ruan  0332',
                    'operational_data' => [
                        'recycling_credit_system' => true,
                        'support_screening_centers' => true,
                        'recycling_credit_system_residual_percent' => [
                            'plastic' => 22,
                            'glass' => 22,
                            'metal' => 22,
                        ],
                    ],
                    'residual_object_system' => 'Qualquer resíduo',
                ],
            ]);

            $ecoDutyFour = EcoDuty::create([
                'eco_ruleset_id' => $ecoRulesetTwo->id,
                'managing_organization_id' => $managingOrganizationOneSupervisingOrganizationTwo->id,
                'managing_code' => uniqid('MANAGING_CODE_'),
                'status' => EcoDutyStatus::getRandomValue(),
                'metadata' => [
                    'url_name' => 'texto qualquer',
                    'url_page' => 'uma url qualquer',
                    'description' => 'Alguma descrição desse sistema',
                    'interloctor' => [
                        'name' => 'Nome do interlocutor',
                        'email' => 'interlocutor@email.com',
                        'phone' => '00 0 00000000',
                        'document' => Utils::generateCpf(),
                        'registration_document' => '1234567',
                    ],
                    'system_name' => 'Teste Ruan  0332',
                    'operational_data' => [
                        'recycling_credit_system' => true,
                        'support_screening_centers' => true,
                        'recycling_credit_system_residual_percent' => [
                            'plastic' => 22,
                            'paper' => 22,
                            'metal' => 22,
                        ],
                    ],
                    'residual_object_system' => 'Qualquer resíduo',
                ],
            ]);

            $ecoDutyFive = EcoDuty::create([
                'eco_ruleset_id' => $ecoRulesetTwo->id,
                'managing_organization_id' => $managingOrganizationTwoSupervisingOrganizationTwo->id,
                'managing_code' => uniqid('MANAGING_CODE_'),
                'status' => EcoDutyStatus::getRandomValue(),
                'metadata' => [
                    'url_name' => 'texto qualquer',
                    'url_page' => 'uma url qualquer',
                    'description' => 'Alguma descrição desse sistema',
                    'interloctor' => [
                        'name' => 'Nome do interlocutor',
                        'email' => 'interlocutor@email.com',
                        'phone' => '00 0 00000000',
                        'document' => Utils::generateCpf(),
                        'registration_document' => '1234567',
                    ],
                    'system_name' => 'Teste Ruan  0332',
                    'operational_data' => [
                        'recycling_credit_system' => true,
                        'support_screening_centers' => true,
                        'recycling_credit_system_residual_percent' => [
                            'plastic' => 22,
                            'paper' => 22,
                            'glass' => 22,
                        ],
                    ],
                    'residual_object_system' => 'Qualquer resíduo',
                ],
            ]);

            $quantityOfEcoDutyReviews = rand(50, 100);

            for ($i = 0; $i < $quantityOfEcoDutyReviews; $i++) {
                EcoDutyReview::create([
                    'eco_duty_id' => array_random([$ecoDutyOne->id, $ecoDutyTwo->id, $ecoDutyThree->id, $ecoDutyFour->id, $ecoDutyFive->id]),
                    'reviewer_user_id' => $supervisingOrganizationOne->users()->inRandomOrder()->first()->id,
                    'sequence_number' => rand(1, $quantityOfEcoDutyReviews),
                    'type' => EcoDutyReviewType::getRandomValue(),
                    'reviewed_at' => now(),
                    'external_id' => null,
                    'comments' => 'Aqui vai estrar algum comentário relevante...',
                    'metadata' => [
                        'key' => 'Não sei o que vai estar aqui',
                        'other_key' => [
                            'key' => 'Alguma coisa aninhada',
                            'other_key' => 'Outra coisa aninhada',
                        ],
                    ],
                ]);
            }
        }

        $ecoDuties = EcoDuty::all();

        foreach ($ecoDuties as $ecoDuty) {
            $materialTypesCodes = array_keys($ecoDuty->metadata['operational_data']['recycling_credit_system_residual_percent']);
            $materialTypes = MaterialType::whereIn('code', $materialTypesCodes)->get();

            foreach ($materialTypes as $materialType) {
                LiabilityDeclaration::create([
                    'eco_duty_id' => $ecoDuty->id,
                    'material_type_id' => $materialType->id,
                    'mass_kg' => rand(10000, 100000),
                ]);
            }
        }
    }
}
