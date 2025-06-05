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
                'firstname' => 'Test',
                'lastname' => 'User',
                'fullname' => 'Test User',
                'email' => 'featuretest@email.com',
                'phone' => '1234567890',
                'birthday' => '1990-01-01',
                'password' => 'passwordMMM111!',
                'role' => 'driver',
            ],
            'newFind' => [
                'firstname' => 'Test',
                'lastname' => 'User',
                'email' => 'featuretest@email.com'
            ],
            'update' => [
                'firstname' => 'Updated',
                'lastname' => 'User',
                'fullname' => 'Updated User',
                'email' => 'featuretest@email.com',
                'phone' => '0987654321',
                'birthday' => '1990-01-01',
                'password' => 'passwordMMM111!',
                'role'=> 'driver',
            ],
            'updateFind'=> [
                'firstname' => 'Updated',
                'lastname' => 'User',
                'email' => 'featuretest@email.com'
            ]
        ];
    }

}