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
            ['firstname' => 'Company', 'email' => 'company@gmail.com', 'password' => '123456', 'role' => 3],
            //['firstname' => 'Driver', 'email' => 'driver@gmail.com', 'password' => '123456', 'role' => 4],
        ];

        foreach ($users as $userData) {

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'firstname' => $userData['firstname'], 
                    'password' => Hash::make($userData['password'])
                    ] 
            );

            // It fixes a bug
            $user->password = Hash::make($userData['password']);
            $user->save();

            // Assign roles
            $user->roles()->sync($userData['role']);
        }

    }

}
