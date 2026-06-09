<?php

namespace Tests\Feature\Api\V1\Todo;

use App\Models\User;
use App\Models\UserTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class TodoTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    private function makeTask(int $userId, array $attrs = []): UserTask
    {
        return UserTask::create(array_merge([
            'unique_code' => (string) Str::uuid(),
            'user_id'     => $userId,
            'title'       => 'Task',
            'status'      => 'open',
        ], $attrs));
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/todo')->assertStatus(401);
    }

    public function test_inactive_blocked_409(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company', ['is_active' => false]));
        $this->getJson('/api/v1/todo')->assertStatus(409);
    }

    public function test_lists_only_own_tasks(): void
    {
        $me    = $this->makeUserWithRole('company');
        $other = $this->makeUserWithRole('company');

        $this->makeTask($me->id);
        $this->makeTask($me->id);
        $this->makeTask($other->id);

        Sanctum::actingAs($me);

        $this->getJson('/api/v1/todo')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_filter_by_entity(): void
    {
        $me = $this->makeUserWithRole('company');
        $this->makeTask($me->id, ['entity' => 'driver']);
        $this->makeTask($me->id, ['entity' => 'vehicle']);
        $this->makeTask($me->id, ['entity' => 'company']);

        Sanctum::actingAs($me);

        $this->getJson('/api/v1/todo/driver')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/todo/vehicle')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/todo/company')->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_filter_by_status(): void
    {
        $me = $this->makeUserWithRole('company');
        $this->makeTask($me->id, ['status' => 'open']);
        $this->makeTask($me->id, ['status' => 'done']);

        Sanctum::actingAs($me);

        $this->getJson('/api/v1/todo?status=done')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', 'done');
    }

    public function test_show_own_task(): void
    {
        $me   = $this->makeUserWithRole('company');
        $task = $this->makeTask($me->id, ['title' => 'Renew medical card']);

        Sanctum::actingAs($me);

        $this->getJson('/api/v1/todo/'.$task->id)
            ->assertOk()
            ->assertJsonPath('data.id', $task->id)
            ->assertJsonPath('data.title', 'Renew medical card');
    }

    public function test_show_404_for_foreign_task(): void
    {
        $me      = $this->makeUserWithRole('company');
        $other   = $this->makeUserWithRole('company');
        $foreign = $this->makeTask($other->id);

        Sanctum::actingAs($me);

        $this->getJson('/api/v1/todo/'.$foreign->id)->assertStatus(404);
    }

    public function test_admin_sees_all_tasks(): void
    {
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
        $this->makeTask($u1->id);
        $this->makeTask($u2->id);

        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/todo')->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_admin_sees_owner_and_can_filter_by_user(): void
    {
        $a = $this->makeUserWithRole('company', ['email' => 'owner-a@example.com']);
        $b = $this->makeUserWithRole('company');
        $this->makeTask($a->id);
        $this->makeTask($b->id);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/todo')->assertOk()->assertJsonCount(2, 'data');
        $this->getJson('/api/v1/todo?user_id='.$a->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.owner.email', 'owner-a@example.com');
    }
}
