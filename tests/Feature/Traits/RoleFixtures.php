<?php

namespace Tests\Feature\Traits;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Support\Facades\DB;

trait RoleFixtures
{
    protected function seedRoles(): array
    {
        $defs = [
            ['title' => 'Admin',   'slug' => 'admin'],
            ['title' => 'Manager', 'slug' => 'manager'],
            ['title' => 'Company', 'slug' => 'company'],
            ['title' => 'Driver',  'slug' => 'driver'],
        ];

        $byslug = [];
        foreach ($defs as $d) {
            $byslug[$d['slug']] = Role::firstOrCreate(
                ['slug' => $d['slug']],
                ['title' => $d['title']],
            );
        }
        return $byslug;
    }

    protected function makeUserWithRole(string $slug, array $attrs = []): User
    {
        $roles = $this->seedRoles();
        $user = User::factory()->create(array_merge([
            'is_active' => true,
        ], $attrs));

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[$slug]->id,
        ]);

        return $user->fresh();
    }
}
