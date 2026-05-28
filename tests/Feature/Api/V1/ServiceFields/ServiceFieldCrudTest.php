<?php

namespace Tests\Feature\Api\V1\ServiceFields;

use App\Models\ReferenceFormField;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class ServiceFieldCrudTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/service-fields')->assertStatus(401);
    }

    public function test_company_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/service-fields')->assertStatus(403);
    }

    public function test_admin_full_crud(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $created = $this->postJson('/api/v1/admin/service-fields', [
            'title' => 'License Number', 'slug' => 'license_number', 'entity' => 'driver', 'type' => 'text',
        ])->assertStatus(201)->assertJsonPath('data.slug', 'license_number')->json('data');

        $this->putJson('/api/v1/admin/service-fields/'.$created['id'], ['title' => 'New Title'])
            ->assertOk()->assertJsonPath('data.title', 'New Title');

        $this->getJson('/api/v1/admin/service-fields/'.$created['id'])
            ->assertOk()->assertJsonPath('data.title', 'New Title');

        $this->deleteJson('/api/v1/admin/service-fields/'.$created['id'])->assertNoContent();
        $this->assertDatabaseMissing('ref_form_fields', ['id' => $created['id']]);
    }

    public function test_uniqueness_on_slug(): void
    {
        ReferenceFormField::create(['title' => 'X', 'slug' => 'x', 'entity' => 'driver']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->postJson('/api/v1/admin/service-fields', ['title' => 'X2', 'slug' => 'x', 'entity' => 'driver'])
            ->assertStatus(422)->assertJsonValidationErrors('slug');
    }
}
