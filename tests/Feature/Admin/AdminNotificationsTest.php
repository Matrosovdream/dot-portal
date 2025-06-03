<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;

class AdminNotificationsTest extends TestCase
{

    protected $initialPath = '/dashboard';

    protected $user_id = 1;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::find( $this->user_id );
    }

    // Front page
    public function test_index_page(): void
    {
        $path = $this->initialPath.'/notifications-manager';
        $response = $this->actingAs($this->user)->get($path);
        $response->assertStatus(200);
    }
    



}