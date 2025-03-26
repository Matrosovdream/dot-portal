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
            ['title' => 'Company', 'slug' => 'company'],
            ['title' => 'Driver', 'slug' => 'driver'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']], // Check by slug
                ['title' => $role['title']] // Create or update
            );
        }
    }

}
