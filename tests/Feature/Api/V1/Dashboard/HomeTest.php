<?php

namespace Tests\Feature\Api\V1\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class HomeTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/dashboard/home')->assertStatus(401);
    }

    public function test_inactive_blocked_409(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company', ['is_active' => false]));
        $this->getJson('/api/v1/dashboard/home')->assertStatus(409);
    }

    public function test_admin_payload_includes_admin_kpis(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/dashboard/home')
            ->assertOk()
            ->assertJsonPath('data.role', 'admin')
            ->assertJsonStructure(['data' => ['role', 'widgets' => ['kpis', 'recent_requests']]]);
    }

    public function test_admin_payload_includes_charts(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/dashboard/home')
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['widgets' => ['charts' => [['key', 'type', 'title', 'labels', 'datasets']]]],
            ])
            ->assertJsonPath('data.widgets.charts.0.key', 'requests_by_status');
    }

    public function test_manager_payload_is_admin_shape(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('manager'));

        $this->getJson('/api/v1/dashboard/home')
            ->assertOk()
            ->assertJsonPath('data.role', 'manager')
            ->assertJsonStructure(['data' => ['widgets' => ['kpis', 'recent_requests']]]);
    }

    public function test_company_payload_includes_todo_and_banner(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/dashboard/home')
            ->assertOk()
            ->assertJsonPath('data.role', 'company')
            ->assertJsonStructure(['data' => ['widgets' => ['kpis', 'todo_summary', 'banner_new_company']]])
            ->assertJsonPath('data.widgets.banner_new_company', true);
    }

    public function test_driver_payload_has_todo(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('driver'));

        $this->getJson('/api/v1/dashboard/home')
            ->assertOk()
            ->assertJsonPath('data.role', 'driver')
            ->assertJsonStructure(['data' => ['widgets' => ['todo_summary' => ['open', 'overdue']]]]);
    }
}
