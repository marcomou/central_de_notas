<?php

namespace App\Http\Controllers;

use App\Events\ManagingOrganizationCreatead;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $userData = $request->validated()['user'];
        $user = User::where('email', $userData['email'])
            ->orWhere('federal_registration', $userData['federal_registration'])
            ->get()
            ->first();

        if ($user && $user->federal_registration === $userData['federal_registration'] && $user->email !== $userData['email']) {
            throw new UnprocessableEntityHttpException("The federal_registration {$userData['federal_registration']} has different email.");
        }

        try {
            DB::beginTransaction();

            if (!$user) {
                $user = User::create($userData);
            }

            $organization = Organization::create(
                $request->validated() +
                    [
                        'code' => uniqid(),
                        'simple_tax_type' => rand(1, 0)
                    ]
            );

            $organization->users()->attach($user);

            event(new ManagingOrganizationCreatead($organization));

            DB::commit();

            return new OrganizationResource($organization);
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }
}
