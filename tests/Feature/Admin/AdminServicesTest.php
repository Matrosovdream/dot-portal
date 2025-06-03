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

        $data = $this->getNewRecordValues();

        // Perform the POST request
        $response = $this->actingAs($this->user)->
            post(
                $this->getRoute('store'),
                $data
        );

        // Verify the record exists
        $record = $this->findRecord($data);

        // Assert
        $this->assertNotNull($record, 'Record was not created successfully.');

        if ($record) {
            $this->createdRecords[] = $record;
        }

        // Clean up
        $this->deleteAllRecords();

    }

    public function test_update_record(): void
    {

        $data = $this->getNewRecordValues();

        // Create a record to update
        $record = $this->createRecord( $data );

        // Prepare updated data
        $dataUpdated = $this->getUpdateRecordValues();

        // Perform the PUT request
        $response = $this->actingAs($this->user)->
            post(
                route($this->routes['update'], $record->id), 
                $dataUpdated
        );

        // Verify the record was updated
        $updatedRecord = $this->findRecord($dataUpdated);

        // Assert
        $this->assertNotNull($updatedRecord, 'Record was not updated successfully.');

        // Clean up
        $this->deleteAllRecords();

    }

    public function test_delete_record(): void
    {
        $data = $this->getNewRecordValues();

        // Create a record to update
        $record = $this->createRecord( $data );

        // Perform the DELETE request
        $response = $this->actingAs($this->user)->
            delete(
                route($this->routes['destroy'], $record->id)
        );
        
        // Verify the record was deleted
        $deletedRecord = $this->findRecord($data);

        // Assert
        $this->assertNull($deletedRecord, 'Record was not deleted successfully.');
    }

    
    public function getNewRecordValues(): array
    {
        return [
            'name' => "recordTestCreate",
            'slug' => 'record-test-create',
            'is_paid' => 1,
            'group_id' => 1,
        ];
    }

    public function getUpdateRecordValues(): array
    {
        return [
            'name' => "recordTestUpdate",
            'slug' => 'record-test-update',
            'is_paid' => 0,
            'group_id' => 2,
        ];
    }

}