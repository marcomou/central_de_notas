<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organization extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'federal_registration',
        'legal_name',
        'front_name',
        'legal_type_id',
        'getherer_id',
    ];

    protected $fillable = [
        'federal_registration',
        'legal_name',
        'front_name',
        'legal_type_id',
        'getherer_id',
    ];

    protected $with = [
        'legalType',
    ];

    protected $appends = [
        'is_supervising_organization',
        'is_managing_organization',
        'is_federal_organization',
    ];

    public function setFederalRegistrationAttribute($value)
    {
        $this->attributes['federal_registration'] = preg_replace('/[^0-9]/', '', (string) $value);
    }

    public function getGethererGuidAttribute($value)
    {
        return $value;
    }

    public function getEcoDuties($ecoDuties = null)
    {
        if ($this->isFederalOrganization())
            return EcoDuty::all();

        if ($this->isSupervisingOrganization())
            return EcoDuty::whereIn('eco_ruleset_id', $this->ecoRulesets->modelKeys())->get();

        if ($this->isManagingOrganization())
            return $this->ecoDuties;

    }

    public function getGetheres($getherers = null)
    {
        if ($this->isFederalOrganization())
            return self::whereNotNull('getherer_id')->get();

        if ($this->isSupervisingOrganization()) {
            $ecoDuties = EcoDuty::whereIn('eco_ruleset_id', $this->ecoRulesets->modelKeys())->get();
            
            return self::whereIn('id', $ecoDuties->pluck('managing_organization_id'))->get();
        }

        if ($this->isManagingOrganization())
            return self::where('id', $this->id)->get();
        
    }

    public function getIsSupervisingOrganizationAttribute(): bool
    {
        return $this->isSupervisingOrganization();
    }

    public function getIsManagingOrganizationAttribute(): bool
    {
        return $this->isManagingOrganization();
    }

    public function getIsFederalOrganizationAttribute(): bool
    {
        return $this->isFederalOrganization();
    }

    // TODO copy method getBestAddress() from other projects

    public function economicAtivities(): BelongsToMany
    {
        return $this->belongsToMany(EconomicActivity::class)
            ->withPivot('is_primary');
    }

    public function legalType(): BelongsTo
    {
        return $this->belongsTo(LegalType::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function primaryAddress(): HasOne
    {
        return $this->fiscalAddress();
    }

    public function fiscalAddress(): HasOne
    {
        return $this->hasOne(Address::class)->sourcedByTreasury();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function ecoDuties(): HasMany
    {
        return $this->hasMany(EcoDuty::class, 'managing_organization_id');
    }

    public function ecoSystems(): HasMany
    {
        return $this->hasMany(EcoSystem::class, 'supervising_organization_id');
    }

    public function ecoRulesets(): HasManyThrough
    {
        return $this->hasManyThrough(EcoRuleset::class, EcoSystem::class, 'supervising_organization_id');
    }

    public function invoiceFiles()
    {
        return $this->hasMany(InvoiceFile::class, 'sent_by_organization_id');
    }

    public function isSupervisingOrganization(): bool
    {
        return $this->ecoSystems()->exists();
    }

    public function isManagingOrganization(): bool
    {
        return $this->ecoDuties()->exists();
    }

    public function isFederalOrganization(): bool
    {
        return $this->federal_registration === config('app.federal_registration_mma');
    }
}
