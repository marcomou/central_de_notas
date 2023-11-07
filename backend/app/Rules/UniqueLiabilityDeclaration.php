<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueLiabilityDeclaration implements Rule
{
    private ?string $materialType;

    private ?string $ecoDuty;

    private ?string $ecoMembership;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        ?string $materialType = '',
        ?string $ecoDuty,
        ?string $ecoMembership = ''
    ) {
        $this->materialType = $materialType;
        $this->ecoDuty = $ecoDuty;
        $this->ecoMembership = $ecoMembership;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ecoMembership = $this->ecoMembership;

        return DB::table('liability_declarations')
            ->where('material_type_id', $this->materialType)
            ->where('eco_duty_id', $this->ecoDuty)
            ->when(
                $this->ecoMembership,
                function ($query) use ($ecoMembership) {
                    $query->where('eco_membership_id', $ecoMembership);
                },
                function ($query) {
                    $query->whereNull('eco_membership_id');
                }
            )->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Liability declaration must be unique by material_type.';
    }
}
