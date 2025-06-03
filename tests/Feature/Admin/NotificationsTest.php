<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;

class NotificationsTest extends TestCase
{

    protected $initialPath = '/dashboard';

    protected $admin_id = 1;

    protected $userAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAdmin = User::find( $this->admin_id );
    }

    // Front page
    public function test_front_page(): void
    {
        $response = $this->actingAs($this->userAdmin)->get($this->initialPath);
        $response->assertStatus(200);
    }
    



}