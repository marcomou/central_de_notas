<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrganizationAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->organizationPermited($request->organization))
            return $next($request);

        return abort(Response::HTTP_FORBIDDEN, 'Organization not allowed!');
    }

    private function organizationPermited(Organization $organization): bool
    {
        return $organization->isFederalOrganization() ||
            $organization->isSupervisingOrganization() ||
            $organization->isManagingOrganization();
    }
}
