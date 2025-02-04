<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $items = [
            [
                'title' => 'Simplex Notice -Office Updates: Dallas & Los Angeles',
                'message' => 'We hope everyone is staying safe and doing well.<br><br>Here is the latest update on our office statuses:<br><br>- Dallas Office: Due to the winter storm, the office remains closed today. Everyone is working remotely, ensuring seamless business continuity and client support.<br><br>- Los Angeles Office: The office is open today and fully operational.<br><br>We appreciate your cooperation and flexibility during these times. <br><br>Please stay safe!',
                'type' => 'info',
                'status' => 'unread',
                'user_id' => 1,
                'user_id_to' => 3,
            ],
            [
                'title' => 'Simplex Notice -Office Closure: Houston',
                'message' => 'Due to road conditions still impacted by snow and ice, our Houston office will remain closed today, Wednesday, January 22nd.<br><br>Our team is working remotely and is fully available to assist you. Please feel free to reach out to us for any support you may need.<br><br>We will continue to monitor the situation and provide updates if further closures are necessary.<br><br>Please stay safe!',
                'type' => 'info',
                'status' => 'unread',
                'user_id' => 1,
                'user_id_to' => 3,
            ],
        ];

        foreach ($items as $item) {
            Notification::create($item);
        }

    }
}
