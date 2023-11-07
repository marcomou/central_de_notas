<?php

namespace App\Services;

use App\Enums\EcoMembershipRole;
use App\Models\EcoMembership;
use App\Models\LegalType;
use App\Models\Organization;

class OrganizationService
{

    public function createRecyclerIfNotExists(array $organizationDraft)
    {
        return $this->createOrganizationIfNotExists($organizationDraft, EcoMembershipRole::RECYCLER);
    }

    public function createOperatorIfNotExists(array $organizationDraft)
    {
        return $this->createOrganizationIfNotExists($organizationDraft, EcoMembershipRole::OPERATOR);
    }

    public function createOrganizationIfNotExists(array $organizationDraft, string $ecoMembershipRole): ?Organization
    {
        $federalTaxid = $organizationDraft["federal_taxid"];

        if (strlen($federalTaxid) < 11 || strlen($federalTaxid) > 14) {
            return null;
        }

        $organization = Organization::where('federal_registration', $federalTaxid)->first();

        if (is_null($organization)) {
            $legalType = LegalType::where('name', "Empresa privada")->first();

            Organization::create([
                'federal_registration' => $federalTaxid,
                'front_name' => $organizationDraft['fantasy_name'],
                'legal_name' => $organizationDraft['name'],
                'legal_type_id' => $legalType->id,
            ]);

            $organization = Organization::where('federal_registration', $federalTaxid)->first();

            EcoMembership::create([
                'member_role' => $ecoMembershipRole,
                'member_organization_id' => $organization->id,
                'extra' => [
                    'zip_code' => $organizationDraft['address_postal_code'],
                    'city' => $organizationDraft['address_city_name'],
                    'street' => $organizationDraft['address_street'],
                    'state' => $organizationDraft['address_state'],
                    'number' => $organizationDraft['address_number'],
                    // 'complement' => null,
                ],
            ]);
        }

        return $organization;
    }
}
