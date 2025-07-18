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
            ['firstname' => 'Company dot #18235', 'email' => 'company18235@gmail.com', 'password' => '123456', 'role' => 3,
                'company' => [
                    'name' => 'Company dot #18235',
                    'phone' => '+381123456789',
                    'dot_number' => '18235',
                    'mc_number' => '',
                ]
            ],
            ['firstname' => 'Company dot #44', 'email' => 'company44@gmail.com', 'password' => '123456', 'role' => 3,
                'company' => [
                    'name' => 'Company dot #44',
                    'phone' => '+381123456789',
                    'dot_number' => '44',
                    'mc_number' => '',
                ]
            ],
            ['firstname' => 'Company dot #363', 'email' => 'company363@gmail.com', 'password' => '123456', 'role' => 3,
                'company' => [
                    'name' => 'Company dot #363',
                    'phone' => '+381123456789',
                    'dot_number' => '363',
                    'mc_number' => '',
                ]
            ],
            ['firstname' => 'Driver', 'email' => 'driver@gmail.com', 'password' => '123456', 'role' => 4],
        ];

        $companies = $this->generateCompanies([6500, 6550]);
        $users = array_merge($users, $companies);

        // user and company with dot 109163, for delay testing
        $users[] = ['firstname' => 'Company dot #109163', 'email' => 'company363@gmail.com', 'password' => '123456', 'role' => 3,
            'company' => [
                'name' => 'Company dot #109163',
                'phone' => '+381123456789',
                'dot_number' => '109163',
                'mc_number' => '',
            ]
        ];

        foreach ($users as $userData) {

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'firstname' => $userData['firstname'], 
                    'password' => Hash::make($userData['password'])
                    ] 
            );

            $user->save();

            // Add company details if the user is a company
            if (
                $userData['role'] == 3 &&
                isset($userData['company'])
                ) {
                $user->company()->updateOrCreate(
                    ['user_id' => $user->id], $userData['company']
                );
            }        

            // Assign roles
            $user->roles()->sync($userData['role']);
        }

    }

    private function generateCompanies( $range=[6500, 6600] ) {

        $users = [];
        for ($i = $range[0]; $i < $range[1]; $i++) {

            $users[] = [
                'firstname' => 'Company dot #' . $i,
                'email' => 'company' . $i . '@gmail.com',
                'password' => '123456',
                'role' => 3, // Assuming role 3 is for companies
                'company' => [
                    'name' => 'Company dot #' . $i,
                    'phone' => '+3811'.$i,
                    'dot_number' => $i,
                    'mc_number' => '',
                ]
            ];

        }

        return $users;

    }

}
