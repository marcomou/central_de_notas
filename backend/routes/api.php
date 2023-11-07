<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\EcoDutyController;
use App\Http\Controllers\EcoDutyReviewController;
use App\Http\Controllers\EcoMembershipController;
use App\Http\Controllers\EconomicActivityController;
use App\Http\Controllers\EcoRulesetController;
use App\Http\Controllers\EcoSystemController;
use App\Http\Controllers\HomologationDiagnosticController;
use App\Http\Controllers\HomologationProcessController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LegalTypeController;
use App\Http\Controllers\LiabilityDeclarationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MaterialTypeController;
use App\Http\Controllers\OperationMassController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Passport::routes(function ($router) {
    $router->forAccessTokens();
    $router->forTransientTokens();
});

Route::post('register', RegisterController::class)->name('register');

Route::post('forgot-password', ForgotPasswordController::class)->name('password.email');
Route::post('reset-password', ResetPasswordController::class)->name('password.reset');

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('legal_types', LegalTypeController::class)->name('legal_types');
Route::get('economic_activities', EconomicActivityController::class)->name('economic_activities');
Route::get('locations', LocationController::class)->name('locations');

Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [InvoiceController::class, 'list'])->name('list');
    Route::post('/', [InvoiceController::class, 'upload'])->name('upload');
    Route::get('{access_key}', [InvoiceController::class, 'details'])->name('details');
    Route::delete('{file_guid}', [InvoiceController::class, 'deleteInvoiceFile'])->name('delete_file');
});

Route::prefix('organizations/{organization}')->name('organizations.')->group(function () {

    Route::middleware('organizationAllowed')->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('outgoing_masses', [DashboardController::class, 'outgoingMasses'])->name('outgoing_masses');
        Route::get('quantity_eco_membership_by_roles', [DashboardController::class, 'quantityEcoMembershipByRoles'])->name('eco_memberships_by_role');
        Route::get('operation_masses_by_material_types', [DashboardController::class, 'operationMassesByMaterialTypes'])->name('operation_masses_by_material_types');
        Route::get('operation_masses_by_operators', [DashboardController::class, 'operationMassesByOperators'])->name('operation_masses_by_operators');
        Route::get('quantity_invoices_by_status', [DashboardController::class, 'quantityInvoicesByStatus'])->name('invoices_by_status');
        Route::get('quantity_collidences_by_organizations', [DashboardController::class, 'quantityCollidencesByOrganizations'])->name('collidences_by_organizations');
    });

    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'organizationList'])->name('list');
    });

    Route::get('eco_duties', [OrganizationController::class, 'ecoDuties'])->name('organizations.eco_duties');
    Route::get('eco_systems', [OrganizationController::class, 'ecoSystems'])->name('organizations.eco_systems');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [OrganizationController::class, 'users'])->name('index');
        Route::post('{user}', [OrganizationController::class, 'attachUser'])->name('attach')->whereUuid(['user']);
        Route::delete('{user}', [OrganizationController::class, 'detachUser'])->name('detach')->scopeBindings();
    });

    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [OrganizationController::class, 'addresses'])->name('index');
        Route::get('/fiscal', [OrganizationController::class, 'fiscalAddress'])->name('fiscal');
        Route::get('/primary', [OrganizationController::class, 'primaryAddress'])->name('primary');
    });
});

Route::prefix('homologation_processes/{homologation_process}')->name('homologation_processes.')->group(function () {
    Route::prefix('document_types')->name('document_types.')->group(function () {
        Route::get('/', [HomologationProcessController::class, 'documentTypes'])->name('.index');
        Route::prefix('{document_type')->group(function () {
            Route::post('/', [HomologationProcessController::class, 'attachDocumentType'])->name('attach');
            Route::put('/', [HomologationProcessController::class, 'updateDocumentType'])->name('update');
            Route::delete('/', [HomologationProcessController::class, 'detachDocumentType'])->name('detach');
        });
    });
});

Route::prefix('eco_duties/{eco_duty}')->name('eco_duties.')->group(function () {
    Route::get('reviews', [EcoDutyController::class, 'reviews'])->name('reviews');
    Route::get('result-by-operators', [EcoDutyController::class, 'quantityByOperator'])->name('quantity_by_operator');
    Route::get('result-by-materials', [EcoDutyController::class, 'quantityByMaterialTypes'])->name('quantity_by_material_types');
    Route::get('eco_memberships', [EcoDutyController::class, 'ecoMemberships'])->name('eco_emberships');
    Route::get("liability_declarations", [EcoDutyController::class, 'liabilityDeclarations'])->name('liability_declarations');
});

Route::prefix('eco_memberships/{eco_membership}')->name('eco_memberships.')->group(function () {
    Route::get("contacts", [EcoMembershipController::class, 'contacts'])->name('contacts');
    Route::get("invoices", [EcoMembershipController::class, 'invoices'])->name('invoices');
    Route::get("documents", [EcoMembershipController::class, 'documents'])->name('documents');
    Route::get("liability_declarations", [EcoMembershipController::class, 'liabilityDeclarations'])->name('liability_declarations');

    Route::prefix('operation_masses')->name('operation_masses.')->group(function () {
        Route::get('/', [EcoMembershipController::class, 'operationMasses'])->name('index');
        Route::get('resume', [EcoMembershipController::class, 'resumeOperationMasses'])->name('resume');
    });

    Route::prefix('homologation')->name('homologation.')->group(function () {
        Route::get("diagnostics", [EcoMembershipController::class, 'homologationDiagnostics'])->name('diagnostics');
    });  
});

Route::apiResources([
    'homologation_processes' => HomologationProcessController::class,
    'material_types' => MaterialTypeController::class,
    'operation_masses' => OperationMassController::class,
    'homologation_diagnostics' => HomologationDiagnosticController::class,
    'liability_declarations' => LiabilityDeclarationController::class,
    'contacts' => ContactController::class,
    'organizations' => OrganizationController::class,
    'users' => UserController::class,
    'eco_systems' => EcoSystemController::class,
    'addresses' => AddressController::class,
    'eco_rulesets' => EcoRulesetController::class,
    'eco_duties' => EcoDutyController::class,
    'eco_memberships' => EcoMembershipController::class,
    'eco_duty_reviews' => EcoDutyReviewController::class,
    'document_types' => DocumentTypeController::class,
    'documents' => DocumentController::class,
]);
