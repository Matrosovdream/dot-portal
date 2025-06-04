<?php

namespace Tests\Feature\Admin;

use App\Models\Request;
use Tests\Feature\Traits\EntityTestable;
use Tests\TestCase;
use App\Models\User;

class AdminRequestTest extends TestCase
{

    use EntityTestable;

    protected $user_id = 1;
    protected $user;
    protected $routes = [
        'index' => 'dashboard.requestmanage.index',
        'create' => 'dashboard.requestmanage.create',
        'store' => 'dashboard.requestmanage.store',
        'edit' => 'dashboard.requestmanage.edit',
        'update' => 'dashboard.requestmanage.update',
        'destroy' => 'dashboard.requestmanage.destroy',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Model set
        $this->model = new Request();

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

    /*
    public function test_store_record(): void
    {

        $this->storeRecordTest(
            $this->getRoute('store'),
            ['new' => $this->getValues()['new']],
            true
        );

    }

    public function test_update_record(): void
    {

        $this->updateRecordTest(
            $this->getRoute('store'),
            $this->routes['update'],
            ['new' => $this->getValues()['new'], 'update' => $this->getValues()['update']],
            true
        );

    }

    public function test_delete_record(): void
    {

        $this->deleteRecordTest(
            $this->routes['destroy'],
            ['new' => $this->getValues()['new']],
            true
        );

    }
    */    

    protected function getValues(): array
    {
        return [
            'new' => [
                'user_id' => $this->user_id,
                'status_id' => 1,
                'service_id' => 1,
            ],
            'update' => [
                'user_id' => $this->user_id,
                'status_id' => 2,
                'service_id' => 2,
            ],
        ];
    }

}