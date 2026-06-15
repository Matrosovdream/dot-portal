<?php

namespace Tests\Feature\Api\V1\Dashboard;

use App\Models\Driver;
use App\Models\DriverDocument;
use App\Models\File;
use App\Models\UserTask;
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
            ->assertJsonStructure(['data' => ['role', 'widgets' => ['kpis', 'charts', 'recent_requests']]])
            ->assertJsonPath('data.widgets.kpis.0.key', 'users')
            ->assertJsonPath('data.widgets.kpis.1.key', 'companies')
            ->assertJsonPath('data.widgets.kpis.2.key', 'requests')
            ->assertJsonPath('data.widgets.kpis.3.key', 'drivers')
            ->assertJsonPath('data.widgets.kpis.4.key', 'vehicles')
            ->assertJsonPath('data.widgets.kpis.5.key', 'revenue');
    }

    public function test_admin_payload_includes_charts(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/dashboard/home')
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['widgets' => ['charts' => [['key', 'type', 'title', 'labels', 'datasets']]]],
            ])
            ->assertJsonPath('data.widgets.charts.0.key', 'requests_by_status')
            ->assertJsonPath('data.widgets.charts.1.key', 'requests_timeseries')
            ->assertJsonPath('data.widgets.charts.2.key', 'revenue_timeseries');
    }

    public function test_manager_payload_is_admin_shape(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('manager'));

        // Manager shares the admin builder — charts must be present, not just kpis/recent_requests.
        $this->getJson('/api/v1/dashboard/home')
            ->assertOk()
            ->assertJsonPath('data.role', 'manager')
            ->assertJsonStructure(['data' => ['widgets' => ['kpis', 'charts', 'recent_requests']]]);
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

    public function test_driver_payload_has_documents_and_tasks(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('driver'));

        $this->getJson('/api/v1/dashboard/home')
            ->assertOk()
            ->assertJsonPath('data.role', 'driver')
            ->assertJsonStructure(['data' => ['widgets' => ['kpis', 'recent_documents', 'recent_tasks']]])
            ->assertJsonPath('data.widgets.kpis.0.key', 'documents');
    }

    public function test_driver_documents_and_tasks_are_ordered_with_name_fallback(): void
    {
        $user = $this->makeUserWithRole('driver');

        // The driver's own Driver row (saved quietly to skip observers/Scout indexing).
        $driver = new Driver(['user_id' => $user->id]);
        $driver->saveQuietly();

        // Three documents with controlled created_at; the middle one has a null title to
        // exercise the title -> filename fallback.
        $this->makeDocument($driver->id, 'Old Doc', 'old.pdf', now()->subDays(2));
        $this->makeDocument($driver->id, null, 'insurance.pdf', now()->subDay());
        $this->makeDocument($driver->id, 'New Doc', 'new.pdf', now());

        // Overdue, upcoming, and undated — the undated one must sort last.
        UserTask::create(['unique_code' => 'task-overdue', 'user_id' => $user->id, 'title' => 'Overdue task', 'status' => 'open', 'due_date' => now()->subDays(2)]);
        UserTask::create(['unique_code' => 'task-soon', 'user_id' => $user->id, 'title' => 'Soon task', 'status' => 'open', 'due_date' => now()->addDays(2)]);
        UserTask::create(['unique_code' => 'task-nodue', 'user_id' => $user->id, 'title' => 'No due task', 'status' => 'open', 'due_date' => null]);

        Sanctum::actingAs($user->fresh());

        $res = $this->getJson('/api/v1/dashboard/home')->assertOk();

        // Documents KPI counts all three.
        $res->assertJsonPath('data.widgets.kpis.0.key', 'documents')
            ->assertJsonPath('data.widgets.kpis.0.value', 3);

        // Newest first, with the filename used when the title is null.
        $res->assertJsonPath('data.widgets.recent_documents.0.name', 'New Doc')
            ->assertJsonPath('data.widgets.recent_documents.1.name', 'insurance.pdf')
            ->assertJsonPath('data.widgets.recent_documents.2.name', 'Old Doc');

        // Soonest due first, undated last.
        $res->assertJsonPath('data.widgets.recent_tasks.0.title', 'Overdue task')
            ->assertJsonPath('data.widgets.recent_tasks.1.title', 'Soon task')
            ->assertJsonPath('data.widgets.recent_tasks.2.title', 'No due task');
    }

    private function makeDocument(int $driverId, ?string $title, string $filename, $createdAt): void
    {
        $file = new File([
            'filename'  => $filename,
            'path'      => 'docs/'.$filename,
            'size'      => '1024',
            'extension' => 'pdf',
        ]);
        $file->saveQuietly();

        $doc = new DriverDocument([
            'driver_id' => $driverId,
            'file_id'   => $file->id,
            'title'     => $title,
            'type'      => 'license',
            'extension' => 'pdf',
        ]);
        // Pin timestamps so created_at ordering is deterministic.
        $doc->timestamps = false;
        $doc->created_at = $createdAt;
        $doc->updated_at = $createdAt;
        $doc->save();
    }
}
