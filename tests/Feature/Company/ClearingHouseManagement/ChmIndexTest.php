<?php

namespace Tests\Feature\Company\ClearingHouseManagement;

use Tests\Feature\Traits\EntityTestable;
use Tests\TestCase;
use App\Models\User;


class ChmIndexTest extends TestCase
{
    use EntityTestable;

    protected $user_id = 3;
    protected $user;
    protected $routes = [
        'index' => 'dashboard.clearinghouse.index',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Model set
        $this->model = new User();

        // User set
        $this->user = User::find( $this->user_id );
    }

    // Index page
    public function test_index_page(): void
    {
        $response = $this->actingAs($this->user)->get( $this->getRoute('index') );
        $response->assertStatus(200);
    }

}