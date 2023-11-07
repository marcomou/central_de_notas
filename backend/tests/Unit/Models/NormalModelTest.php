<?php

namespace Tests\Unit\Models;

use App\Utils\Utils;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use Tests\Stubs\Models\Model;
use Tests\TestCase;

class NormalModelTest extends TestCase
{
    use DatabaseMigrations;

    private Model $model;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('audit.console', true);

        Model::makeTable();
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Model::dropTable();

        parent::tearDown();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_if_uuid_created_is_valid()
    {
        $model = Model::create([
            'name' => 'teste',
        ]);

        $this->assertTrue(Utils::validateUuid($model->id));
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_if_soft_delete_works()
    {
        if (Model::usingDeletedAtTrait()) {
            $model = Model::create([
                'name' => 'teste',
            ]);

            $model->delete();

            $this->assertSoftDeleted(
                table: 'model',
                data: [
                    'id' => $model->id,
                    'name' => $model->name,
                ],
            );
        }
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_if_model_create_working()
    {
        $model = Model::create([
            'name' => 'teste',
        ]);

        $this->assertDatabaseCount(
            table: 'model',
            count: 1,
        );

        $this->assertDatabaseHas(
            table: 'model',
            data: [
                'id' => $model->id,
                'name' => $model->name,
            ],
        );
    }

    public function test_check_create_audit_record()
    {
        $model = Model::create(['name' => 'teste']);

        $this->assertCount(
            expectedCount: 1,
            haystack: $model->audits()->where('event', 'created')->get(),
        );
    }

    public function test_check_update_audit_record()
    {
        $model = Model::create(['name' => 'teste']);
        $model->update([
            'name' => 'updated',
        ]);

        $this->assertCount(
            expectedCount: 1,
            haystack: $model->audits()->where('event', 'updated')->get(),
        );
    }

    public function test_check_delete_audit_record()
    {
        $model = Model::create(['name' => 'teste']);
        $model->delete();

        if (Model::usingDeletedAtTrait())
            $this->assertCount(
                expectedCount: 1,
                haystack: $model->audits()->where('event', 'deleted')->get(),
            );

        else $this->markTestSkipped('Model not using soft delete.');
    }

    public function test_check_restore_audit_record()
    {
        $model = Model::create(['name' => 'teste']);

        $model->delete();

        if (Model::usingDeletedAtTrait()) {
            $model->restore();

            $this->assertCount(
                expectedCount: 1,
                haystack: $model->audits()->where('event', 'restored')->get(),
            );
        } else $this->markTestSkipped('Model not using soft delete.');
    }
}
