<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserTaskRepo;

class UserTaskSeeder extends Seeder
{

    protected $taskRepo;

    public function __construct()
    {
        $this->taskRepo = new UserTaskRepo();
    }

    public function run()
    {
        $tasks = [
            [
                'title' => 'Medical card',
                'description' => '',
                'category' => 'driver',
                'subcategory' => 'medical_card',
                'status' => 'pending',
                'due_date' => now()->addDays(7),
                'priority' => 'normal',
                'link' => null,
                'entity' => 'driver',
                'entity_id' => 1,
            ],
            [
                'title' => 'MVR',
                'description' => '',
                'category' => 'driver',
                'subcategory' => 'mvr',
                'status' => 'completed',
                'due_date' => now()->addDays(14),
                'priority' => 'normal',
                'link' => null,
                'entity' => 'driver',
                'entity_id' => 1,
            ]
        ];

        $users = [3,4];

        foreach ($tasks as $task) {
            
            foreach ($users as $userId) {
                $taskData = array_merge($task, [
                    'user_id' => $userId,
                    'assigned_to' => $userId,
                ]);

                $this->taskRepo->create($taskData);
            }

        }

    }

}
