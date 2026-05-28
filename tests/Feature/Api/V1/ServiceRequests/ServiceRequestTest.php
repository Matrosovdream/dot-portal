<?php

namespace Tests\Feature\Api\V1\ServiceRequests;

use App\Models\Request as ServiceRequest;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class ServiceRequestTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/service-requests')->assertStatus(401);
    }

    public function test_admin_blocked_from_user_endpoint(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/service-requests')->assertStatus(403);
    }

    public function test_company_sees_only_own_requests(): void
    {
        $a = $this->makeUserWithRole('company');
        $b = $this->makeUserWithRole('company');
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 1]);
        ServiceRequest::create(['user_id' => $b->id, 'service_id' => $svc->id, 'status_id' => 1]);

        Sanctum::actingAs($a);
        $this->getJson('/api/v1/service-requests')
            ->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_store_creates_with_default_price_from_service(): void
    {
        $user = $this->makeUserWithRole('company');
        $svc  = Service::create(['name' => 'Reg', 'slug' => 'reg', 'price' => 79.99]);

        Sanctum::actingAs($user);
        $this->postJson('/api/v1/service-requests', ['service_id' => $svc->id])
            ->assertStatus(201)
            ->assertJsonPath('data.service_id', $svc->id)
            ->assertJsonPath('data.price', 79.99);

        $this->assertDatabaseHas('requests', [
            'user_id' => $user->id, 'service_id' => $svc->id, 'status_id' => 1, 'is_paid' => false,
        ]);
    }

    public function test_store_requires_service_id_existing(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->postJson('/api/v1/service-requests', ['service_id' => 99999])
            ->assertStatus(422)->assertJsonValidationErrors('service_id');
    }

    public function test_show_404_for_foreign_request(): void
    {
        $a = $this->makeUserWithRole('company');
        $b = $this->makeUserWithRole('company');
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        $foreign = ServiceRequest::create(['user_id' => $b->id, 'service_id' => $svc->id, 'status_id' => 1]);

        Sanctum::actingAs($a);
        $this->getJson('/api/v1/service-requests/'.$foreign->id)->assertStatus(404);
    }

    public function test_show_returns_own_request(): void
    {
        $a = $this->makeUserWithRole('company');
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        $own = ServiceRequest::create(['user_id' => $a->id, 'service_id' => $svc->id, 'status_id' => 1]);

        Sanctum::actingAs($a);
        $this->getJson('/api/v1/service-requests/'.$own->id)
            ->assertOk()->assertJsonPath('data.id', $own->id);
    }
}
