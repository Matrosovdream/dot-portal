<?php

namespace Tests\Feature\Api\V1\AdminRequests;

use App\Models\Request as ServiceRequest;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class AdminRequestTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/requests')->assertStatus(401);
    }

    public function test_company_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/requests')->assertStatus(403);
    }

    public function test_admin_lists_all_requests(): void
    {
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        $a = $this->makeUserWithRole('company');
        ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 1]);
        ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 2]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/requests')
            ->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_filter_by_status_id(): void
    {
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        $a = $this->makeUserWithRole('company');
        ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 1]);
        ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 2]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/requests?status_id=2')
            ->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status_id', 2);
    }

    public function test_show_returns_request_with_service(): void
    {
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        $a = $this->makeUserWithRole('company');
        $req = ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 1]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/requests/'.$req->id)
            ->assertOk()
            ->assertJsonPath('data.id', $req->id)
            ->assertJsonPath('data.service_id', $svc->id)
            ->assertJsonPath('data.service.id', $svc->id);
    }

    public function test_update_persists_price_and_paid(): void
    {
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        $a = $this->makeUserWithRole('company');
        $req = ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 1, 'is_paid' => false]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->putJson('/api/v1/admin/requests/'.$req->id, ['price' => 99.99, 'is_paid' => true])
            ->assertOk()
            ->assertJsonPath('data.price', 99.99)
            ->assertJsonPath('data.is_paid', true);

        $this->assertDatabaseHas('requests', ['id' => $req->id, 'is_paid' => true]);
    }

    public function test_update_status(): void
    {
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        $a = $this->makeUserWithRole('company');
        $req = ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 1]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->postJson('/api/v1/admin/requests/'.$req->id.'/status', ['status_id' => 3])
            ->assertOk()->assertJsonPath('data.status_id', 3);

        $this->assertDatabaseHas('requests', ['id' => $req->id, 'status_id' => 3]);
    }

    public function test_destroy(): void
    {
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        $a = $this->makeUserWithRole('company');
        $req = ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 1]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->deleteJson('/api/v1/admin/requests/'.$req->id)->assertNoContent();
        $this->assertDatabaseMissing('requests', ['id' => $req->id]);
    }
}
