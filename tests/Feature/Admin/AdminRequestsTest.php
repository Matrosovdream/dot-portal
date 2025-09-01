<?php

namespace Tests\Feature\Admin;

use Tests\Feature\Traits\EntityTestable;
use Tests\TestCase;
use App\Models\Request;
use App\Models\User;


class AdminRequestsTest extends TestCase
{

    use EntityTestable;

    protected $user_id = 1;
    protected $user;
    protected $routes = [
        'index' => 'dashboard.requestmanage.index',
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
        */

    protected function getValues(): array
    {
        return [
            'new' => [
            ],
            'newFind' => [
            ],
            'update' => [
            ],
            'updateFind'=> [
            ]
        ];
    }

}