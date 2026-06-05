<?php

namespace App\Services\DummyData\Generators;

use App\Models\UserPaymentCard;
use App\Models\UserPaymentHistory;
use App\Models\UserSubscription;
use App\Services\DummyData\AssignmentPicker;
use App\Services\DummyData\DummyConfig;
use Faker\Generator as Faker;

/**
 * Subscribes each dummy company to the plan that fits its fleet size and
 * back-fills a few months of billing history. Requires seeded subscription
 * plans (SubscriptionSeeder); skips gracefully when none exist.
 *
 * @return array{subscriptions:int, payment_history:int}
 */
class SubscriptionGenerator
{
    public function __construct(
        private AssignmentPicker $picker,
        private Faker $faker,
    ) {}

    public function generate(): array
    {
        $subs = 0;
        $history = 0;

        foreach ($this->picker->dummyCompanyUsers() as $company) {
            $driverCount = count($this->picker->driverIdsForCompany($company->id));
            $plan = $this->picker->subscriptionForDrivers($driverCount);

            if ($plan === null) {
                continue;
            }

            $drivers     = max($driverCount, 1);
            $perDriver   = (float) $plan->price_per_driver;
            $price       = round($perDriver * $drivers, 2);
            $start       = now()->subMonths(DummyConfig::BILLING_HISTORY_MONTHS);
            $card        = UserPaymentCard::query()->where('user_id', $company->id)->orderBy('id')->first();
            $paymentMethodId = $this->picker->pick($this->picker->paymentMethodIds, 1);

            UserSubscription::updateOrCreate(
                ['user_id' => $company->id],
                [
                    'subscription_id'  => $plan->id,
                    'price'            => $price,
                    'price_per_driver' => $perDriver,
                    'drivers_number'   => $drivers,
                    'discount'         => 0,
                    'payment_card_id'  => $card?->id,
                    'start_date'       => $start->toDateString(),
                    'next_date'        => now()->addMonth()->toDateString(),
                    'end_date'         => now()->addYear()->toDateString(),
                    'status'           => 'active',
                ],
            );
            $subs++;

            for ($m = DummyConfig::BILLING_HISTORY_MONTHS; $m >= 1; $m--) {
                $date = now()->subMonths($m);
                $transactionId = "DUMMY-{$company->id}-" . $date->format('Ym');

                UserPaymentHistory::updateOrCreate(
                    ['transaction_id' => $transactionId],
                    [
                        'user_id'           => $company->id,
                        'payment_method_id' => $paymentMethodId,
                        'subscription_id'   => $plan->id,
                        'type'              => 'subscription',
                        'amount'            => $price,
                        'payment_date'      => $date->toDateString(),
                        'status'            => $this->faker->randomElement(['success', 'success', 'success', 'fail']),
                        'notes'             => 'Monthly subscription charge',
                    ],
                );
                $history++;
            }
        }

        return ['subscriptions' => $subs, 'payment_history' => $history];
    }
}
