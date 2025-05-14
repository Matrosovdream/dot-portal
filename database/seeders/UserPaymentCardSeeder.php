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
            ['user_id' => 3, 'card_number' => '411112254532', 'card_holder_name' => 'Stan Matrosov', 'expiry_date' => '03-2029', 'payment_method_id' => 1, 'primary' => true],
            ['user_id' => 3, 'card_number' => '411212259855', 'card_holder_name' => 'Stan Matrosov', 'expiry_date' => '05-2031', 'payment_method_id' => 1,],
        ];
        
        foreach ($subs as $item) {
            UserPaymentCard::firstOrCreate($item);
        }

    }
}
