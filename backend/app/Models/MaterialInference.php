<?php

namespace App\Models;

class MaterialInference extends Model
{

    protected $table = 'material_inferences';

    protected $fillable = [
        'description',
        'material_type_id',
        'is_packaging_source',
    ];

    public function scopeByDescription($query, $description)
    {
        return $query->where('description', $description);
    }

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class, 'material_type_id');
    }

}
