<?php

namespace Tests\Feature\Api\V1\Admin\Gateways;

use App\Models\PaymentGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class GatewayTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    private function seedGateway(array $o = []): PaymentGateway
    {
        return PaymentGateway::create(array_merge([
            'name'        => 'Stripe',
            'slug'        => 'stripe',
            'description' => 'Stripe gateway',
            'is_active'   => true,
        ], $o));
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/gateways')->assertStatus(401);
    }

    public function test_company_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/gateways')->assertStatus(403);
    }

    public function test_inactive_admin_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin', ['is_active' => false]));
        $this->getJson('/api/v1/admin/gateways')->assertStatus(409);
    }

    public function test_admin_can_list(): void
    {
        $this->seedGateway(['name' => 'Findme', 'slug' => 'findme']);
        $this->seedGateway(['name' => 'Authorize', 'slug' => 'authorize']);

        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/admin/gateways')
            ->assertOk()
            ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'is_active']]])
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['slug' => 'findme'])
            ->assertJsonFragment(['slug' => 'authorize']);
    }

    public function test_admin_list_filters_by_q(): void
    {
        $this->seedGateway(['name' => 'Findme', 'slug' => 'findme']);
        $this->seedGateway(['name' => 'Authorize', 'slug' => 'authorize']);

        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/admin/gateways?q=Find')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['slug' => 'findme']);
    }
}
