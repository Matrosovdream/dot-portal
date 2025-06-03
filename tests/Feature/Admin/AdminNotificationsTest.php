<?php

namespace Tests\Feature\Admin;

use App\Models\Notification;
use Tests\Feature\Traits\EntityTestable;
use Tests\TestCase;
use App\Models\User;

class AdminNotificationsTest extends TestCase
{

    use EntityTestable;

    protected $initialPath = '/dashboard/notifications-manager';
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
        $path = $this->initialPath.'/create';
        $response = $this->actingAs($this->user)->get($path);
        $response->assertStatus(200);
    }

    public function test_store_record(): void
    {
        // Prepare test data
        $data = [
            'user_id' => $this->user_id, 
            'type' => 1,
            'title' => 'recordTestCreate',
            'message' => 'message test create',
        ];

        // Perform the POST request
        $response = $this->actingAs($this->user)->
            post(
                route('dashboard.notifications-manage.store'), 
                $data
        );

        // Verify the record exists
        $record = $this->findRecord($data);

        // Assert
        $this->assertNotNull($record, 'Record was not created successfully.');

        if ($record) {
            $this->createdRecords[] = $record;
        }
    }

    public function test_update_record(): void
    {

        $data = [
            'user_id' => $this->user_id,
            'type' => 1,
            'title' => 'recordTestUpdate',
            'message' => 'message test update',
        ];

        // Create a record to update
        $record = $this->createRecord( $data );

        // Prepare updated data
        $dataUpdated = [
            'type' => 2,
            'title' => 'updatedRecordTest',
            'message' => 'updated message test',
        ];

        // Perform the PUT request
        $response = $this->actingAs($this->user)->
            post(
                route('dashboard.notifications-manage.update', $record->id), 
                $dataUpdated
        );

        // Verify the record was updated
        $updatedRecord = $this->findRecord($dataUpdated);

        // Assert
        $this->assertNotNull($updatedRecord, 'Record was not updated successfully.');

    }

    public function test_delete_record(): void
    {
        // Prepare test data
        $data = [
            'user_id' => $this->user_id, 
            'type' => 1,
            'title' => 'recordTestDelete',
            'message' => 'message test delete',
        ];

        // Create a record to delete
        $record = $this->createRecord( $data );

        // Perform the DELETE request
        $response = $this->actingAs($this->user)->
            delete(
                route('dashboard.notifications-manage.destroy', $record->id)
        );

        // Verify the record was deleted
        $deletedRecord = $this->findRecord($data);

        // Assert
        $this->assertNull($deletedRecord, 'Record was not deleted successfully.');
    }

}