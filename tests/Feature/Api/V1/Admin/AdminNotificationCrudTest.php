<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class AdminNotificationCrudTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    private string $base = '/api/v1/admin/notifications-manage';

    private function makeNotification(User $creator, array $overrides = []): Notification
    {
        return Notification::create(array_merge([
            'title' => 'T',
            'message' => 'M',
            'type' => 'info',
            'status' => 'unread',
            'user_id' => $creator->id,
            'user_id_to' => null,
        ], $overrides));
    }

    public function test_guest_blocked(): void
    {
        $this->getJson($this->base)->assertStatus(401);
    }

    public function test_inactive_admin_blocked_409(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin', ['is_active' => false]));

        $this->getJson($this->base)->assertStatus(409);
    }

    public function test_company_forbidden_403(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson($this->base)->assertStatus(403);
    }

    public function test_manager_forbidden_403(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('manager'));

        $this->getJson($this->base)->assertStatus(403);
    }

    public function test_admin_index_lists(): void
    {
        $admin = $this->makeUserWithRole('admin');
        Sanctum::actingAs($admin);

        $this->makeNotification($admin);
        $this->makeNotification($admin);

        $this->getJson($this->base)
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_admin_store_sets_creator(): void
    {
        $admin = $this->makeUserWithRole('admin');
        Sanctum::actingAs($admin);

        $this->postJson($this->base, [
            'title' => 'Hi',
            'message' => 'Body',
            'type' => 'info',
        ])
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'Hi');

        $this->assertDatabaseHas('notifications', [
            'title' => 'Hi',
            'user_id' => $admin->id,
        ]);
    }

    public function test_store_validation_422(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->postJson($this->base, [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'message']);
    }

    public function test_admin_show(): void
    {
        $admin = $this->makeUserWithRole('admin');
        Sanctum::actingAs($admin);

        $n = $this->makeNotification($admin);

        $this->getJson($this->base . '/' . $n->id)
            ->assertOk()
            ->assertJsonPath('data.id', $n->id);

        $this->getJson($this->base . '/999999')->assertStatus(404);
    }

    public function test_admin_update(): void
    {
        $admin = $this->makeUserWithRole('admin');
        Sanctum::actingAs($admin);

        $n = $this->makeNotification($admin);

        $this->putJson($this->base . '/' . $n->id, [
            'title' => 'New',
            'message' => 'New body',
        ])
            ->assertOk()
            ->assertJsonPath('data.title', 'New');

        $this->assertDatabaseHas('notifications', [
            'id' => $n->id,
            'title' => 'New',
        ]);

        $this->putJson($this->base . '/' . $n->id, [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'message']);
    }

    public function test_admin_destroy(): void
    {
        $admin = $this->makeUserWithRole('admin');
        Sanctum::actingAs($admin);

        $n = $this->makeNotification($admin);

        $this->deleteJson($this->base . '/' . $n->id)->assertNoContent();

        $this->assertDatabaseMissing('notifications', ['id' => $n->id]);
    }
}
