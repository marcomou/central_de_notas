<?php

namespace App\Http\Controllers;

use App\Models\EcoDuty;
use App\Models\Organization;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService;
    }

    public function quantityEcoMembershipByRoles(Request $request, Organization $organization)
    {
        $ecoDuties = $organization->getEcoDuties($request->ecoDuties)->pluck('id')->join(',');

        $data = $this->dashboardService->ecoMembershipsByRoles(
            ecoDuties: $ecoDuties
        );

        return response()->json([
            'data' => $data,
        ]);
    }

    public function outgoingMasses(Request $request, Organization $organization)
    {
        $getherers = $organization->getGetheres($request->getherers)->pluck('getherer_id')->join(',');
        $ecoDuties = $organization->getEcoDuties($request->ecoDuties)->pluck('id')->join(',');
        $params = $request->only(["issued_at_start", "issued_at_end", "state", "operator"]);

        $data = $this->dashboardService->outgoingMasses(
            getherers: $getherers, 
            ecoDuties: $ecoDuties,
            params: $params
        );

        return response()->json([
            'data' => $data,
        ]);
    }

    public function quantityInvoicesByStatus(Request $request, Organization $organization)
    {
        $ecoDuties = $organization->getEcoDuties($request->ecoDuties)->pluck('id')->join(',');
        $params = $request->only(["issued_at_start", "issued_at_end", "state"]);

        $data = $this->dashboardService->invoicesByStatus(
            ecoDuties: $ecoDuties,
            params: $params
        );

        return response()->json([
            'data' => $data,
        ]);
    }

    public function quantityCollidencesByOrganizations(Request $request, Organization $organization)
    {
        $ecoDuties = $organization->getEcoDuties($request->ecoDuties)->pluck('id')->join(',');
        $params = $request->only(["issued_at_start", "issued_at_end", "state"]);

        $data = $this->dashboardService->collidencesByOrganization(
            ecoDuties: $ecoDuties,
            params: $params
        );

        return response()->json([
            'data' => $data,
        ]);
    }

    public function operationMassesByMaterialTypes(Request $request, Organization $organization)
    {
        $ecoDuties = $organization->getEcoDuties($request->ecoDuties)->pluck('id')->join(',');

        $data = $this->dashboardService->operationMassesByMaterialTypes(
            ecoDuties: $ecoDuties
        );

        return response()->json([
            'data' => $data,
        ]);
    }

    public function operationMassesByOperators(Request $request, Organization $organization)
    {
        $ecoDuties = $organization->getEcoDuties($request->ecoDuties)->pluck('id')->join(',');

        $data = $this->dashboardService->operationMassesByOperators(
            ecoDuties: $ecoDuties
        );

        return response()->json([
            'data' => $data
        ]);
    }
}
