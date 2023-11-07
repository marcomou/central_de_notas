<?php

namespace Tests\Feature;

use App\Enums\EcoMembershipRole;
use App\Models\EcoDuty;
use App\Models\EcoMembership;
use App\Models\Organization;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_upload_invoices()
    {
        $invoceOne = UploadedFile::fake()->create('teste-invoice-service.xml', 0, 'application/xml');

        $organization = Organization::factory()->create();
        $ecoDuty = EcoDuty::factory()->create(['managing_organization_id' => $organization->id]);
        $operator = EcoMembership::factory()->create([
            'eco_duty_id' => $ecoDuty->id,
            'member_role' => EcoMembershipRole::OPERATOR
        ]);

        $response = $this->json('POST', route('invoices.upload'), [
            'invoices' => [
                $invoceOne,
            ],
            'sent_by' => $operator->memberOrganization->id,
            'eco_duty' => $ecoDuty->id,
            'getherer' => $organization->getherer_id,
        ]);

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_upload_invoices_because_invoice_has_mimetype_not_valid()
    {
        $invoceOne = UploadedFile::fake()->create('invoice-one.pdf', 0, 'application/html');

        $organization = Organization::factory()->create();

        $response = $this->json('POST', route('invoices.upload'), [
            'invoices' => [
                $invoceOne,
            ],
            'getherer' => $organization->getherer_id,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('invoices.0');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_upload_invoices_because_invoice_is_empty()
    {
        $organization = Organization::factory()->create();

        $response = $this->json('POST', route('invoices.upload'), [
            'invoices' => null,
            'getherer' => $organization->getherer_id,
        ]);

        $response->assertUnprocessable();

        $response = $this->json('POST', route('invoices.upload'), [
            'invoices' => [],
            'getherer' => $organization->getherer_id,
        ]);

        $response->assertUnprocessable();
    }

    public function test_get_invoices_successfully()
    {
        $organization = Organization::factory()->create();

        $response = $this->json('GET', route('invoices.list'), [
            'getherers' => $organization->getherer_id,
        ]);

        $response->assertOk();
    }

    public function test_get_invoices_of_organization_successfully()
    {
        $ecoDuty = EcoDuty::factory()->create();

        $response = $this->json('GET', route('organizations.invoices.list', $ecoDuty->managingOrganization));

        $response->assertOk();
    }

    public function test_successfully_get_invoice()
    {
        $response = $this->json('GET', route('invoices.details', '50180705758486000145550010000035121000035124'));

        $response->assertOk();
    }
}
