<?php

namespace Tests\Feature\Api\V1\Files;

use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class FileTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->postJson('/api/v1/files')->assertStatus(401);
        $this->getJson('/api/v1/files/1')->assertStatus(401);
    }

    public function test_inactive_blocked_409(): void
    {
        // POST (no model binding) to ensure user.isActive middleware fires before SubstituteBindings.
        Sanctum::actingAs($this->makeUserWithRole('company', ['is_active' => false]));
        $this->postJson('/api/v1/files')->assertStatus(409);
    }

    public function test_upload_stores_file_and_row(): void
    {
        Storage::fake('local');

        $u = $this->makeUserWithRole('company');
        Sanctum::actingAs($u);

        $file = UploadedFile::fake()->create('doc.pdf', 10);

        $this->postJson('/api/v1/files', ['file' => $file])
            ->assertStatus(201)
            ->assertJsonPath('data.filename', 'doc.pdf');

        $this->assertDatabaseHas('files', [
            'filename' => 'doc.pdf',
            'user_id'  => $u->id,
        ]);

        $row = File::first();
        Storage::disk('local')->assertExists($row->path);
    }

    public function test_upload_validation_422(): void
    {
        $u = $this->makeUserWithRole('company');
        Sanctum::actingAs($u);

        $this->postJson('/api/v1/files', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('file');
    }

    public function test_show_returns_metadata(): void
    {
        $u = $this->makeUserWithRole('company');

        $f = File::create([
            'filename'   => 'a.txt',
            'path'       => 'files/a.txt',
            'type'       => 'file',
            'size'       => '10',
            'extension'  => 'txt',
            'disk'       => 'local',
            'visibility' => 'private',
            'user_id'    => $u->id,
        ]);

        Sanctum::actingAs($u);

        $this->getJson('/api/v1/files/'.$f->id)
            ->assertOk()
            ->assertJsonPath('data.id', $f->id)
            ->assertJsonPath('data.filename', 'a.txt');
    }

    public function test_download_returns_200(): void
    {
        Storage::fake('local');

        $u = $this->makeUserWithRole('company');
        Sanctum::actingAs($u);

        $file = UploadedFile::fake()->create('doc.pdf', 10);

        $id = $this->postJson('/api/v1/files', ['file' => $file])
            ->assertStatus(201)
            ->json('data.id');

        $this->get('/api/v1/files/'.$id.'/download')->assertStatus(200);
    }

    public function test_show_404_for_foreign_file(): void
    {
        $me    = $this->makeUserWithRole('company');
        $other = $this->makeUserWithRole('company');

        $f = File::create([
            'filename'   => 'b.txt',
            'path'       => 'files/b.txt',
            'type'       => 'file',
            'size'       => '10',
            'extension'  => 'txt',
            'disk'       => 'local',
            'visibility' => 'private',
            'user_id'    => $other->id,
        ]);

        Sanctum::actingAs($me);

        $this->getJson('/api/v1/files/'.$f->id)->assertStatus(404);
        $this->get('/api/v1/files/'.$f->id.'/download')->assertStatus(404);
    }
}
