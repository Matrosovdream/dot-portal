<?php

namespace Tests\Feature\Admin;

use App\Models\Request;
use Tests\Feature\Traits\EntityTestable;
use Tests\TestCase;
use App\Models\User;

class AdminSettingsTest extends TestCase
{

    use EntityTestable;

    protected $user_id = 1;
    protected $user;
    protected $routes = [
        'index' => 'dashboard.settings.index',
        'store' => 'dashboard.settings.store',
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
        parent::tearDown();
    }


    // Index page
    public function test_index_page(): void
    {
        $response = $this->actingAs($this->user)->get( $this->getRoute('index') );
        $response->assertStatus(200);
    }

    protected function getValues(): array
    {
        return [
            'new' => [

            ],
            'update' => [

            ],
        ];
    }

}