<?php

namespace Tests\Feature\Admin;

use App\Models\Service;
use Tests\Feature\Traits\EntityTestable;
use Tests\TestCase;
use App\Models\User;


class AdminServicesTest extends TestCase
{

    use EntityTestable;

    protected $user_id = 1;
    protected $user;
    protected $routes = [
        'index' => 'dashboard.services.index',
        'create' => 'dashboard.services.create',
        'store' => 'dashboard.services.store',
        'edit' => 'dashboard.services.edit',
        'update' => 'dashboard.services.update',
        'destroy' => 'dashboard.services.destroy',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Model set
        $this->model = new Service();

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
                'name' => "recordTestCreate",
                'slug' => 'record-test-create',
                'is_paid' => 1,
                'group_id' => 1,
            ],
            'newFind' => [
                'name' => "recordTestCreate",
                'slug' => 'record-test-create',
            ],
            'update' => [
                'name' => "recordTestUpdate",
                'slug' => 'record-test-update',
                'is_paid' => 0,
                'group_id' => 2,
            ],
            'updateFind' => [
                'name' => "recordTestUpdate",
                'slug' => 'record-test-update',
            ],
        ];
    }

}