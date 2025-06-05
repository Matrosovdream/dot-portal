<?php

namespace Tests\Feature\Admin;

use App\Models\RefServiceGroup;
use Tests\Feature\Traits\EntityTestable;
use Tests\TestCase;
use App\Models\User;

class AdminServiceGroupsTest extends TestCase
{

    use EntityTestable;

    protected $user_id = 1;
    protected $user;
    protected $routes = [
        'index' => 'dashboard.servicegroups.index',
        'create' => 'dashboard.servicegroups.create',
        'store' => 'dashboard.servicegroups.store',
        'edit' => 'dashboard.servicegroups.edit',
        'update' => 'dashboard.servicegroups.update',
        'destroy' => 'dashboard.servicegroups.destroy',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Model set
        $this->model = new RefServiceGroup();

        // User set
        $this->user = User::find( $this->user_id );
    }

    protected function tearDown(): void
    {
        // Delete created records
        $this->deleteAllRecords();

        parent::tearDown();
    }


    // Index page
    public function test_index_page(): void
    {
        $response = $this->actingAs($this->user)->get( $this->getRoute('index') );
        $response->assertStatus(200);
    }
    
    // Create page
    public function test_create_page(): void
    {
        $response = $this->actingAs($this->user)->get( $this->getRoute('create') );
        $response->assertStatus(200);
    }

    public function test_store_record(): void
    {

        $this->storeRecordTest(
            $this->getRoute('store'),
            $this->getValues(),
            true
        );

    }

    public function test_update_record(): void
    {

        $this->updateRecordTest(
            $this->getRoute('store'),
            $this->routes['update'],
            $this->getValues(),
            true
        );

    }

    public function test_delete_record(): void
    {

        $this->deleteRecordTest(
            $this->routes['destroy'],
            $this->getValues(),
            true
        );

    }

    protected function getValues(): array
    {
        return [
            'new' => [
                'name' => 'TestServiceGroup',
                'slug' => 'test-service-group',
            ],
            'newFind' => [
                'name' => 'TestServiceGroup',
                'slug' => 'test-service-group',
            ],
            'update' => [
                'name' => 'UpdatedServiceGroup',
                'slug' => 'test-service-group',
            ],
            'updateFind' => [
                'name' => 'UpdatedServiceGroup',
                'slug' => 'test-service-group',
            ]
        ];
    }

}