<?php

namespace Tests\Feature\User;

use Tests\Feature\Traits\EntityTestable;
use Tests\TestCase;
use App\Models\User;

class AdminUsersTest extends TestCase
{

    use EntityTestable;

    protected $user_id = 1;
    protected $user;
    protected $routes = [
        'index' => 'dashboard.users.index',
        'create' => 'dashboard.users.create',
        'store' => 'dashboard.users.store',
        'edit' => 'dashboard.users.edit',
        'update' => 'dashboard.users.update',
        'destroy' => 'dashboard.users.destroy',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Model set
        $this->model = new User();

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
                'firstname' => 'Test',
                'lastname' => 'User',
                'fullname' => 'Test User',
                'email' => 'feature-test@email.com',
                'password' => 'passwordMMM111!',
            ],
            'update' => [
                'firstname' => 'Updated',
                'lastname' => 'User',
                'fullname' => 'Updated User',
                'email' => 'feature-test@email.com',
                'password' => 'passwordMMM111!',
            ],
        ];
    }

}