<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{

    public function run()
    {
        $roles = [
            ['title' => 'Administrator', 'slug' => 'admin'],
            ['title' => 'Manager', 'slug' => 'manager'],
            ['title' => 'User', 'slug' => 'user'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']], // Check by slug
                ['title' => $role['title']] // Create if not found
            );
        }
    }

}
