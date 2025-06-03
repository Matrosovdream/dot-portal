<?php

namespace Tests\Feature\Admin;

use App\Models\Notification;
use Tests\TestCase;
use App\Models\User;

class AdminNotificationsTest extends TestCase
{

    protected $initialPath = '/dashboard/notifications-manager';

    protected $user_id = 1;

    protected $user;
    protected $model;
    protected $createdRecords = [];


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
        $path = $this->initialPath.'/';
        $response = $this->actingAs($this->user)->get($path);
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
            'type' => 1,
            'title' => 'recordTest',
            'message' => 'message test',
        ];

        // Perform the POST request
        $response = $this->actingAs($this->user)->
            post(
                route('dashboard.notifications-manage.store'), 
                $data
        );

        // Verify the record exists
        $record = $this->findRecord($data);

        $this->assertNotNull($record, 'Record was not created successfully.');

        if ($record) {
            $this->createdRecords[] = $record;
        }
    }


    protected function createRecord(array $data = []): Notification
    {
        return $this->model->create($data);
    }

    protected function updateRecord( $record_id, array $data = [] ): Notification
    {
        $record = $this->model->find($record_id);
        $record->update($data);
        return $record->refresh();
    }

    protected function deleteRecord( $record_id ): void
    {
        $record = $this->model->find($record_id);
        $record->delete();
    }

    protected function findRecord(array $filter): ?Notification
    {
        return $this->model->where($filter)->first();
    }

    protected function deleteAllRecords(): void
    {
        foreach ($this->createdRecords as $record) {
            $this->deleteRecord($record->id);
        }
        $this->createdRecords = [];
    }

}