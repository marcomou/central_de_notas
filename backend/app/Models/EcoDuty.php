<?php

namespace App\Models;

use App\Enums\EcoDutyStatus;
use App\Enums\EcoMembershipRole;
use App\Enums\OperationMassType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class EcoDuty extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'eco_ruleset_id',
        'managing_organization_id',
        'status',
        'metadata',
        'managing_code',
    ];

    protected $fillable = [
        'eco_ruleset_id',
        'managing_organization_id',
        'status',
        'metadata',
        'managing_code',
    ];

    protected $casts = [
        'metadata' => 'array',
        'status' => EcoDutyStatus::class,
    ];

    protected $with = [
        'ecoRuleset',
    ];

    public function getMaterialTypesAttribute(): array
    {
        if (is_null($this->metadata)) {
            return [];
        }

        return $this->metadata['operational_data']['recycling_credit_system_residual_percent'];
    }

    public function getMaterialTypesKeysAttribute(): array
    {
        return array_keys($this->material_types);
    }

    #Refactor
    public function getMetaAttribute(): string
    {
        $this->load('operators');

        if (is_null($this->metadata)) {
            return 'Meta a definir';
        }

        $definedGoalsPercent = $this->metadata['operational_data']['recycling_credit_system_residual_percent'];

        $libilityDeclarations = DB::table("liability_declarations")
            ->select(
                'material_types.id',
                'material_types.name',
                'material_types.code',
                'material_types.name',
                'material_type_id',
                DB::raw("SUM(mass_kg) as mass_kg"),
            )
            ->join('material_types', 'material_types.id', 'liability_declarations.material_type_id')
            ->where('eco_duty_id', $this->id)
            ->groupBy('material_type_id')
            ->get();

        $validatedOutgoingOperationMasses = DB::table('operation_masses')
            ->select(
                'material_types.id',
                'material_types.name',
                'material_types.code',
                'material_types.name',
                DB::raw("(CASE WHEN operation_mass_type = '" . OperationMassType::VALIDATED_OUTGOING_WEIGHT . "' THEN SUM(mass_kg) ELSE 0 END) AS mass_kg"),
            )->join('material_types', 'material_types.id', '=', 'operation_masses.material_type_id')
            ->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT)
            ->whereIn('eco_membership_id', $this->operators->modelKeys())
            ->groupBy('operation_mass_type', 'material_type_id')
            ->get();

        $definedGoalsWeightMass = collect($libilityDeclarations)->map(function ($item) use ($definedGoalsPercent, $validatedOutgoingOperationMasses) {

            $massKg = $item->mass_kg * $definedGoalsPercent[$item->code] / 100;

            $done = false;

            $result = [
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'mass_kg' => $massKg,
                'done' => $done,
            ];

            foreach ($validatedOutgoingOperationMasses as $validatedOutgoingOperationMass) {
                if ($validatedOutgoingOperationMass->code === $item->code && $validatedOutgoingOperationMass->mass_kg > $massKg) {
                    $result['done'] = true;
                }
            }

            return $result;
        });

        $resultCount = 0;

        foreach ($definedGoalsWeightMass as $definedGoalWeightMass) {
            if ($definedGoalWeightMass['done'])
                $resultCount++;
        }

        return $resultCount === $definedGoalsWeightMass->count() ? 'Meta alcançada' : 'Meta a definir';
    }

    public function ecoRuleset(): BelongsTo
    {
        return $this->belongsTo(EcoRuleset::class);
    }

    public function ecoMemberships(): HasMany
    {
        return $this->hasMany(EcoMembership::class);
    }

    public function operators(): HasMany
    {
        return $this->ecoMemberships()
            ->byMemberRole(EcoMembershipRole::OPERATOR());
    }

    public function managingOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'managing_organization_id');
    }

    public function liabilityDeclarations(): HasMany
    {
        return $this->hasMany(LiabilityDeclaration::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(EcoDutyReview::class)->latest('sequence_number');
    }
}
