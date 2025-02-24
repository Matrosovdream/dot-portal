<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{

    public function run()
    {
        $users = [
            ['firstname' => 'Admin', 'email' => 'admin@gmail.com', 'password' => '123456', 'role' => 1],
            ['firstname' => 'Manager', 'email' => 'manager@gmail.com', 'password' => '123456', 'role' => 2],
            ['firstname' => 'user', 'email' => 'user@gmail.com', 'password' => '123456', 'role' => 3],
        ];

        foreach ($users as $userData) {

            $user = User::firstOrCreate(
                ['email' => $userData['email']], // Check by email
                ['firstname' => $userData['firstname'], 'password' => Hash::make($userData['password'])] // Create if not found
            );

            // It fixes a bug
            $user->password = Hash::make($userData['password']);
            $user->save();

            // Assign roles
            $user->roles()->sync($userData['role']);
        }

    }

}
