<?php

namespace Tests\Feature\Api\V1\Notifications;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class NotificationTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    private function makeNotification(int $toId, array $attrs = []): Notification
    {
        return Notification::create(array_merge([
            'title'      => 'Hello',
            'message'    => 'A message',
            'type'       => 'system',
            'status'     => 'unread',
            'user_id'    => $toId,
            'user_id_to' => $toId,
        ], $attrs));
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/notifications')->assertStatus(401);
    }

    public function test_inactive_blocked_409(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company', ['is_active' => false]));
        $this->getJson('/api/v1/notifications')->assertStatus(409);
    }

    public function test_lists_only_own_notifications_with_unread_count(): void
    {
        $me    = $this->makeUserWithRole('company');
        $other = $this->makeUserWithRole('company');

        $this->makeNotification($me->id);
        $this->makeNotification($me->id, ['status' => 'read']);
        $this->makeNotification($other->id);

        Sanctum::actingAs($me);

        $this->getJson('/api/v1/notifications')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('unread', 1);
    }

    public function test_mark_single_read(): void
    {
        $me = $this->makeUserWithRole('company');
        $n  = $this->makeNotification($me->id);

        Sanctum::actingAs($me);

        $this->putJson('/api/v1/notifications/'.$n->id.'/read')
            ->assertOk()
            ->assertJsonPath('data.is_read', true);

        $this->assertDatabaseHas('notifications', ['id' => $n->id, 'status' => 'read']);
    }

    public function test_cannot_mark_foreign_notification(): void
    {
        $me    = $this->makeUserWithRole('company');
        $other = $this->makeUserWithRole('company');
        $n     = $this->makeNotification($other->id);

        Sanctum::actingAs($me);

        $this->putJson('/api/v1/notifications/'.$n->id.'/read')->assertStatus(404);
        $this->assertDatabaseHas('notifications', ['id' => $n->id, 'status' => 'unread']);
    }

    public function test_mark_all_read(): void
    {
        $me = $this->makeUserWithRole('company');
        $this->makeNotification($me->id);
        $this->makeNotification($me->id);

        Sanctum::actingAs($me);

        $this->postJson('/api/v1/notifications/read-all')
            ->assertOk()
            ->assertJsonPath('updated', 2);

        $this->assertDatabaseMissing('notifications', ['user_id_to' => $me->id, 'status' => 'unread']);
    }
}
