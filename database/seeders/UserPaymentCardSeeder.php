<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserPaymentCard;

class UserPaymentCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $subs = [
            ['user_id' => 3, 'card_number' => '411112254532', 'card_holder_name' => 'Stan Matrosov', 'expiry_date' => '2025-01-01'],
            ['user_id' => 3, 'card_number' => '411212259855', 'card_holder_name' => 'Stan Matrosov', 'expiry_date' => '2027-03-01'],
        ];
        
        foreach ($subs as $item) {
            UserPaymentCard::firstOrCreate($item);
        }

    }
}
