<?php

namespace Tests\Feature\Api\V1\Documents;

use App\Models\Driver;
use App\Models\DriverDocument;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class DocumentTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    /**
     * Create a File using the REAL verified columns. size is a STRING column.
     * search_index is intentionally NOT set (File::booted() computes it).
     */
    private function makeFile(array $overrides = []): File
    {
        return File::create(array_merge([
            'filename'   => 'Doc A',
            'path'       => 'uploads/a.pdf',
            'type'       => 'application/pdf',
            'size'       => '1024',
            'extension'  => 'pdf',
            'disk'       => 'public',
            'visibility' => 'public',
            'user_id'    => null,
        ], $overrides));
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/documents')->assertStatus(401);
    }

    public function test_inactive_blocked_409(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company', ['is_active' => false]));
        $this->getJson('/api/v1/documents')->assertStatus(409);
    }

    public function test_lists_only_own_documents(): void
    {
        $me = $this->makeUserWithRole('company');
        $other = $this->makeUserWithRole('company');

        $myFile = $this->makeFile(['filename' => 'Mine', 'user_id' => $me->id]);
        $this->makeFile(['filename' => 'Theirs', 'user_id' => $other->id]);

        Sanctum::actingAs($me);

        $this->getJson('/api/v1/documents')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $myFile->id)
            ->assertJsonPath('data.0.name', 'Mine');
    }

    public function test_admin_sees_all(): void
    {
        $userA = $this->makeUserWithRole('company');
        $userB = $this->makeUserWithRole('company');

        $this->makeFile(['filename' => 'A', 'user_id' => $userA->id]);
        $this->makeFile(['filename' => 'B', 'user_id' => $userB->id]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/documents')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_driver_sees_only_their_driver_documents(): void
    {
        $driverUser = $this->makeUserWithRole('driver');

        $driver = Driver::create([
            'user_id'   => $driverUser->id,
            'status_id' => 1,
        ]);

        // A file referenced by the driver's driver_documents (should be visible).
        $linkedFile = $this->makeFile(['filename' => 'CDL', 'user_id' => null]);
        DriverDocument::create([
            'driver_id' => $driver->id,
            'type'      => 'cdl',
            'title'     => 'CDL',
            'file_id'   => $linkedFile->id,
            'extension' => 'pdf',
        ]);

        // An unrelated file the driver must NOT see.
        $this->makeFile(['filename' => 'Other', 'user_id' => null]);

        Sanctum::actingAs($driverUser);

        $this->getJson('/api/v1/documents')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $linkedFile->id);
    }

    public function test_admin_sees_owner_and_can_filter_by_user(): void
    {
        $a = $this->makeUserWithRole('company', ['email' => 'owner-a@example.com']);
        $b = $this->makeUserWithRole('company');
        $this->makeFile(['filename' => 'A', 'user_id' => $a->id]);
        $this->makeFile(['filename' => 'B', 'user_id' => $b->id]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/documents')->assertOk()->assertJsonCount(2, 'data');
        $this->getJson('/api/v1/documents?user_id='.$a->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'A')
            ->assertJsonPath('data.0.owner.email', 'owner-a@example.com');
    }
}
