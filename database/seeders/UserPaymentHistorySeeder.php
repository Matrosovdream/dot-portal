<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserPaymentHistory;

class UserPaymentHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $subs = [
            ['user_id' => 3, 'payment_method_id' => 1, 'subscription_id' => 1, 'type' => 'subscription', 'amount' => 155.00, 'payment_date' => '2021-03-01', 'transaction_id' => '112233', 'status' => 'success', 'notes' => 'Successful payment'],
            ['user_id' => 3, 'payment_method_id' => 1, 'subscription_id' => 1, 'type' => 'subscription', 'amount' => 155.00, 'payment_date' => '2021-04-01', 'transaction_id' => '112234', 'status' => 'success', 'notes' => 'Successful payment'],
            ['user_id' => 3, 'payment_method_id' => 1, 'subscription_id' => 1, 'type' => 'subscription', 'amount' => 155.00, 'payment_date' => '2021-05-01', 'transaction_id' => '112235', 'status' => 'fail', 'notes' => 'Failed payment'],
            ['user_id' => 3, 'payment_method_id' => 1, 'subscription_id' => 1, 'type' => 'subscription', 'amount' => 155.00, 'payment_date' => '2021-06-01', 'transaction_id' => '112236', 'status' => 'success', 'notes' => 'Successful payment'],
            ['user_id' => 3, 'payment_method_id' => 1, 'subscription_id' => 1, 'type' => 'subscription', 'amount' => 155.00, 'payment_date' => '2021-07-01', 'transaction_id' => '112237', 'status' => 'success', 'notes' => 'Successful payment'],
            ['user_id' => 3, 'payment_method_id' => 1, 'subscription_id' => 1, 'type' => 'subscription', 'amount' => 155.00, 'payment_date' => '2021-08-01', 'transaction_id' => '112238', 'status' => 'fail', 'notes' => 'Failed payment'],
            ['user_id' => 3, 'payment_method_id' => 1, 'subscription_id' => 1, 'type' => 'subscription', 'amount' => 155.00, 'payment_date' => '2021-09-01', 'transaction_id' => '112239', 'status' => 'success', 'notes' => 'Successful payment'],
        ];
        
        foreach ($subs as $item) {
            UserPaymentHistory::firstOrCreate($item);
        }

    }
}
