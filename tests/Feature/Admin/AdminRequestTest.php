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
        'show' => 'dashboard.requestmanage.show',
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

    // Show page
    public function test_show_page(): void
    {
        $record = $this->createRecord($this->getValues()['new']);
        $response = $this->actingAs($this->user)->get(
            route($this->routes['show'], ['request_id' => $record->id])
        );
        $response->assertStatus(200);

        // Clean up
        $this->deleteRecord($record->id);
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
                'status_id' => 1,
                'service_id' => 1,
                'is_paid' => false,
            ],
            'update' => [
                'user_id' => $this->user_id,
                'status_id' => 2,
                'service_id' => 2,
                'is_paid'=> true,
            ],
        ];
    }

}