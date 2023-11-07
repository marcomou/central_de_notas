<?php

namespace App\Models;

class MaterialType extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'parent_material_id',
        'code',
        'name',
        'extra',
    ];

    protected $fillable = [
        // 'parent_material_id',
        // 'code',
        'name',
    ];

    protected $casts = [
        'extra' => 'array',
    ];

    const NCM_MATERIALS = [
        'plastic' => [
            '39',
            '40',
        ],
        'paper' => [
            '47',
            '48',
            '49',
        ],
        'glass' => [
            '70',
        ],
        'metal' => [
            '72',
            '73',
            '74',
            '75',
            '76',
            '78',
            '79',
            '80',
            '81',
            '82',
            '83',
        ],
        // 'cdru' => [
        //     '38',
        // ],
    ];

    public static function findMaterialByNcm(string $ncm)
    {
        foreach (static::NCM_MATERIALS as $materialTypeCode => $ncms) {
            foreach ($ncms as $ncmFirstNumbers) {
                $ncmNumbers = substr($ncm, 0, strlen($ncmFirstNumbers));

                if ($ncmFirstNumbers === $ncmNumbers) {
                    return static::where('code', $materialTypeCode)->first();
                }
            }
        }

        return null;
    }

    public function subMaterialTypes()
    {
        return $this->hasMany(MaterialType::class, 'parent_material_id');
    }

    public function materialTypeParent()
    {
        return $this->belongsTo(MaterialType::class, 'parent_material_id');
    }
}
