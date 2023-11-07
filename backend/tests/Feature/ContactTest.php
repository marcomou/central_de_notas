<?php

namespace Tests\Feature;

use App\Enums\ContactType;
use App\Models\Contact;
use App\Models\EcoMembership;
use App\Utils\Utils;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use DatabaseMigrations;

    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_empty_list_contacts()
    {
        $response = $this->json('GET', route('contacts.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_list_contacts()
    {
        $quantity = $this->faker->numberBetween(1, 10);

        Contact::factory($quantity)->create();

        $response = $this->json('GET', route('contacts.index'));

        $response->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount($quantity, 'data');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_store_contact_with_empty_data()
    {
        $data = [
            'eco_membership_id' => EcoMembership::factory()->create()->id,
            'role' => ContactType::getRandomValue(),
            'name' => null,
            'email' => null,
            'phone' => null,
            'document' => null,
        ];

        $response = $this->json('POST', route('contacts.store'), $data);

        $response->assertCreated();
        $this->assertDatabaseHas('contacts', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_store_contact_with_data()
    {
        $data = [
            'eco_membership_id' => EcoMembership::factory()->create()->id,
            'role' => ContactType::getRandomValue(),
            'name' => $this->faker->name(),
            'email' => $this->faker->freeEmail(),
            'phone' => $this->faker->numerify('###########'),
            'document' => Utils::generateCpf(),
        ];

        $response = $this->json('POST', route('contacts.store'), $data);

        $response->assertCreated();
        $this->assertDatabaseHas('contacts', $data);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_update_contacts()
    {
        $contact = Contact::factory()->create();

        $data = [
            'eco_membership_id' => $contact->eco_membership_id,
            'role' => ContactType::getRandomValue(),
            'name' => 'updated',
        ];

        $response = $this->json('PUT', route('contacts.update', $contact), array_merge($contact->toArray(), $data));

        $response->assertOk();
        $this->assertDatabaseHas('contacts', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_show_details()
    {
        $contact = Contact::factory()->create();

        $response = $this->json('GET', route('contacts.show', $contact));

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_details_because_not_exist()
    {
        $response = $this->json('GET', route('contacts.show', $this->faker->uuid()));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_details_because_is_deleted()
    {
        $contact = Contact::factory()->create();
        $contact->delete();

        $response = $this->json('GET', route('contacts.show', $contact));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_destroy_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->json('DELETE', route('contacts.destroy', $contact));

        $response->assertNoContent();
        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_destroy_contact_because_not_exists()
    {
        $response = $this->json('DELETE', route('contacts.destroy', $this->faker->uuid()));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_destroy_contact_because_is_deleted()
    {
        $contact = Contact::factory()->create();
        $contact->delete();

        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);

        $response = $this->json('DELETE', route('contacts.destroy', $contact));

        $response->assertNotFound();
    }
}
