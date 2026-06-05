<?php

namespace App\Services\DummyData\Generators;

use App\Models\Notification;
use App\Models\UserTask;
use App\Repositories\User\UserTaskRepo;
use App\Services\DummyData\AssignmentPicker;
use App\Services\DummyData\DummyConfig;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/**
 * Creates operational tasks and notifications for each dummy company user.
 * Both are count-based idempotent (top-up to target). UserTask.unique_code is
 * NOT NULL/unique with no auto-generation, so we generate it explicitly.
 *
 * @return array{tasks:int, notifications:int}
 */
class TaskNotificationGenerator
{
    private const TASKS = [
        ['title' => 'Medical card renewal', 'category' => 'driver',  'subcategory' => 'medical_card'],
        ['title' => 'MVR review',           'category' => 'driver',  'subcategory' => 'mvr'],
        ['title' => 'License expiring',      'category' => 'driver',  'subcategory' => 'license'],
        ['title' => 'Vehicle inspection',    'category' => 'vehicle', 'subcategory' => 'inspection'],
        ['title' => 'Registration renewal',  'category' => 'vehicle', 'subcategory' => 'registration'],
    ];

    private UserTaskRepo $taskRepo;

    public function __construct(
        private AssignmentPicker $picker,
        private Faker $faker,
    ) {
        $this->taskRepo = new UserTaskRepo();
    }

    public function generate(): array
    {
        $tasks = 0;
        $notifications = 0;

        foreach ($this->picker->dummyCompanyUsers() as $company) {
            $tasks         += $this->generateTasks($company->id);
            $notifications += $this->generateNotifications($company->id);
        }

        return ['tasks' => $tasks, 'notifications' => $notifications];
    }

    private function generateTasks(int $userId): int
    {
        $target   = $this->faker->numberBetween(...DummyConfig::TASKS_PER_COMPANY);
        $existing = UserTask::query()->where('user_id', $userId)->count();
        $count = 0;

        for ($n = $existing; $n < $target; $n++) {
            $template = $this->faker->randomElement(self::TASKS);

            $this->taskRepo->create([
                'unique_code' => strtoupper(Str::random(14)),
                'user_id'     => $userId,
                'assigned_to' => $userId,
                'title'       => $template['title'],
                'description' => 'Auto-generated dummy task',
                'category'    => $template['category'],
                'subcategory' => $template['subcategory'],
                'status'      => $this->faker->randomElement(['pending', 'pending', 'completed']),
                'due_date'    => now()->addDays($this->faker->numberBetween(1, 30)),
                'priority'    => $this->faker->randomElement(['low', 'normal', 'normal', 'high']),
                'entity'      => $template['category'],
                'entity_id'   => null,
            ]);

            $count++;
        }

        return $count;
    }

    private function generateNotifications(int $userId): int
    {
        $target   = $this->faker->numberBetween(...DummyConfig::NOTIFICATIONS_PER_COMPANY);
        $existing = Notification::query()->where('user_id', $userId)->count();
        $count = 0;

        for ($n = $existing; $n < $target; $n++) {
            Notification::create([
                'title'      => $this->faker->randomElement(['Document expiring', 'Payment received', 'New request update', 'Compliance reminder']),
                'message'    => $this->faker->sentence(),
                'type'       => $this->faker->randomElement(['info', 'warning', 'success']),
                'status'     => $this->faker->randomElement(['unread', 'unread', 'read']),
                'user_id'    => $userId,
                'user_id_to' => null,
            ]);

            $count++;
        }

        return $count;
    }
}
