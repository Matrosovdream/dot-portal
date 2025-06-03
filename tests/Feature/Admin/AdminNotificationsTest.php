<?php

namespace Tests\Feature\Admin;

use App\Models\Notification;
use Tests\Feature\Traits\EntityTestable;
use Tests\TestCase;
use App\Models\User;

class AdminNotificationsTest extends TestCase
{

    use EntityTestable;

    protected $user_id = 1;
    protected $user;
    protected $routes = [
        'index' => 'dashboard.notifications-manage.index',
        'create' => 'dashboard.notifications-manage.create',
        'store' => 'dashboard.notifications-manage.store',
        'edit' => 'dashboard.notifications-manage.edit',
        'update' => 'dashboard.notifications-manage.update',
        'destroy' => 'dashboard.notifications-manage.destroy',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Model set
        $this->model = new Notification();

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

    protected function getValues(): array
    {
        return [
            'new' => [
                'user_id' => $this->user_id,
                'type' => 1,
                'title' => "recordTestCreate",
                'message' => 'message test create',
            ],
            'update' => [
                'type' => 2,
                'title' => "recordTestUpdate",
                'message' => 'message test update',
            ],
        ];
    }

}