<?php

namespace App\Services\DummyData;

use App\Models\Driver;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Request;
use App\Models\User;
use App\Models\UserPaymentCard;
use App\Models\UserSubscription;
use App\Models\UserTask;
use App\Models\Vehicle;
use App\Services\DummyData\Generators\CompanyUserGenerator;
use App\Services\DummyData\Generators\DriverGenerator;
use App\Services\DummyData\Generators\OrderGenerator;
use App\Services\DummyData\Generators\PaymentGenerator;
use App\Services\DummyData\Generators\ServiceRequestGenerator;
use App\Services\DummyData\Generators\SubscriptionGenerator;
use App\Services\DummyData\Generators\TaskNotificationGenerator;
use App\Services\DummyData\Generators\VehicleGenerator;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Orchestrates dummy-data generation in dependency order and provides a
 * marker-scoped wipe. Mirrors the Freshdesk DummyDataMigrationService: a thin
 * coordinator over per-entity generators that returns a summary of counts.
 */
class DummyDataGeneratorService
{
    /**
     * @param  array{companies?:int, seed?:int}  $opts
     * @return array<string,mixed>
     */
    public function run(array $opts = []): array
    {
        $faker = FakerFactory::create();
        if (isset($opts['seed'])) {
            $faker->seed((int) $opts['seed']);
        }

        $picker    = new AssignmentPicker();
        $companies = $opts['companies'] ?? DummyConfig::COMPANIES;

        return [
            'companies'           => (new CompanyUserGenerator($picker, $faker))->generate($companies),
            'drivers'             => (new DriverGenerator($picker, $faker))->generate(),
            'vehicles'            => (new VehicleGenerator($picker, $faker))->generate(),
            'payment_cards'       => (new PaymentGenerator($picker, $faker))->generate(),
            'subscriptions'       => (new SubscriptionGenerator($picker, $faker))->generate(),
            'requests'            => (new ServiceRequestGenerator($picker, $faker))->generate(),
            'orders'              => (new OrderGenerator($picker, $faker))->generate(),
            'tasks_notifications' => (new TaskNotificationGenerator($picker, $faker))->generate(),
        ];
    }

    /**
     * Delete everything reachable from a dummy user (email @ the dummy domain),
     * in child-before-parent order, inside a transaction.
     *
     * @return array<string,int>
     */
    public function wipe(): array
    {
        return DB::transaction(function () {
            $userIds = User::query()
                ->where('email', 'like', DummyConfig::emailLikePattern())
                ->pluck('id')->all();

            if (empty($userIds)) {
                return ['users' => 0];
            }

            $driverIds  = Driver::query()->whereIn('company_user_id', $userIds)->orWhereIn('user_id', $userIds)->pluck('id')->all();
            $vehicleIds = Vehicle::query()->whereIn('company_user_id', $userIds)->pluck('id')->all();
            $orderIds   = Order::query()->whereIn('user_id', $userIds)->pluck('id')->all();
            $requestIds = Request::query()->whereIn('user_id', $userIds)->pluck('id')->all();
            $cardIds    = UserPaymentCard::query()->whereIn('user_id', $userIds)->pluck('id')->all();
            $taskIds    = UserTask::query()->whereIn('user_id', $userIds)->orWhereIn('assigned_to', $userIds)->pluck('id')->all();
            $subIds     = UserSubscription::query()->whereIn('user_id', $userIds)->pluck('id')->all();
            $notifIds   = Notification::query()->whereIn('user_id', $userIds)->orWhereIn('user_id_to', $userIds)->pluck('id')->all();

            // --- Child / meta tables (delete first) ---
            foreach ([
                ['driver_documents', 'driver_id', $driverIds],
                ['driver_license', 'driver_id', $driverIds],
                ['driver_cdl_license', 'driver_id', $driverIds],
                ['driver_medical_card', 'driver_id', $driverIds],
                ['driver_drug_test', 'driver_id', $driverIds],
                ['driver_mvr', 'driver_id', $driverIds],
                ['driver_address', 'item_id', $driverIds],
                ['driver_meta', 'item_id', $driverIds],
                ['driver_history', 'item_id', $driverIds],
                ['vehicle_mvr', 'vehicle_id', $vehicleIds],
                ['vehicle_documents', 'vehicle_id', $vehicleIds],
                ['vehicle_inspections', 'vehicle_id', $vehicleIds],
                ['vehicle_driver_history', 'vehicle_id', $vehicleIds],
                ['vehicle_insurance_link', 'vehicle_id', $vehicleIds],
                ['order_items', 'order_id', $orderIds],
                ['order_payments', 'order_id', $orderIds],
                ['order_meta', 'item_id', $orderIds],
                ['request_history', 'request_id', $requestIds],
                ['request_field_values', 'request_id', $requestIds],
                ['request_predefined_values', 'request_id', $requestIds],
                ['request_meta', 'item_id', $requestIds],
                ['user_payment_card_meta', 'card_id', $cardIds],
                ['user_task_meta', 'task_id', $taskIds],
                ['notification_meta', 'item_id', $notifIds],
                ['user_subscription_meta', 'subscription_id', $subIds],
                ['user_subscription_payments', 'user_subscription_id', $subIds],
            ] as [$table, $col, $ids]) {
                $this->del($table, $col, $ids);
            }

            // --- Parents / user-scoped rows ---
            $counts = [
                'vehicles'        => Vehicle::query()->whereIn('id', $vehicleIds)->delete(),
                'drivers'         => Driver::query()->whereIn('id', $driverIds)->delete(),
                'orders'          => Order::query()->whereIn('id', $orderIds)->delete(),
                'requests'        => Request::query()->whereIn('id', $requestIds)->delete(),
                'subscriptions'   => UserSubscription::query()->whereIn('id', $subIds)->delete(),
                'payment_cards'   => UserPaymentCard::query()->whereIn('id', $cardIds)->delete(),
                'payment_history' => $this->delByUser('user_payment_history', $userIds),
                'tasks'           => UserTask::query()->whereIn('id', $taskIds)->delete(),
                'notifications'   => Notification::query()->whereIn('id', $notifIds)->delete(),
            ];

            $this->delByUser('user_company', $userIds);
            $this->delByUser('user_company_address', $userIds);
            $this->delByUser('user_address', $userIds);
            $this->delByUser('user_meta', $userIds);
            DB::table('user_roles')->whereIn('user_id', $userIds)->delete();

            $counts['users'] = User::query()->whereIn('id', $userIds)->delete();

            return $counts;
        });
    }

    private function del(string $table, string $col, array $ids): void
    {
        if (empty($ids) || !Schema::hasColumn($table, $col)) {
            return;
        }

        DB::table($table)->whereIn($col, $ids)->delete();
    }

    private function delByUser(string $table, array $userIds): int
    {
        if (empty($userIds) || !Schema::hasColumn($table, 'user_id')) {
            return 0;
        }

        return DB::table($table)->whereIn('user_id', $userIds)->delete();
    }
}
