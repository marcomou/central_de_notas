<?php

namespace Database\Seeders;

use App\Models\MaterialType;
use Illuminate\Database\Seeder;

class MaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => 'Papel',
                'code' => 'paper',
                'subtypes' => [
                    [
                        'name' => 'Papelão',
                        'code' => 'cardboard'
                    ],
                    [
                        'name' => 'Longa Vida',
                        'code' => 'aseptic-carton'
                    ],
                ]
            ],

            [
                'name' => 'Plástico',
                'code' => 'plastic',
                'subtypes' => [
                    [
                        'name' => 'PVC',
                        'code' => 'plastic-pvc'
                    ],
                    [
                        'name' => 'Plástico multimaterial',
                        'code' => 'plastic-bopp'
                    ],
                    [
                        'name' => 'PS',
                        'code' => 'plastic-ps'
                    ],
                    [
                        'name' => 'PE',
                        'code' => 'plastic-pe'
                    ],
                    [
                        'name' => 'PET',
                        'code' => 'plastic-pet'
                    ],
                    [
                        'name' => 'PEBD',
                        'code' => 'plastic-pebd'
                    ],
                    [
                        'name' => 'PEAD',
                        'code' => 'plastic-pead'
                    ],
                ]
            ],

            [
                'name' => 'Vidro',
                'code' => 'glass',
                'subtypes' => []
            ],

            [
                'name' => 'Metal',
                'code' => 'metal',
                'subtypes' => [
                    [
                        'name' => 'Alumínio',
                        'code' => 'aluminum'
                    ],
                    [
                        'name' => 'Aço e Ferro',
                        'code' => 'steel-iron'
                    ],
                    [
                        'name' => 'Aerosol',
                        'code' => 'aerossol'

                    ]
                ]
            ],

            [
                'name' => 'Outros',
                'code' => 'others',
                'subtypes' => []
            ]
        ])->each(function ($materialTypeDraft) {
            $materialType = $this->updateOrCreate($materialTypeDraft);

            collect($materialTypeDraft['subtypes'])->each(function ($materialTypeSub) use ($materialType) {
                $this->updateOrCreate($materialTypeSub, $materialType->id);
            });
        });
    }

    private function updateOrCreate(array $materialType, string $materialTypeParentId = null)
    {
        return MaterialType::updateOrCreate(['code' => $materialType['code']], [
            'name'=> $materialType['name'],
            'parent_material_id' => $materialTypeParentId,
        ]);
    }
}
