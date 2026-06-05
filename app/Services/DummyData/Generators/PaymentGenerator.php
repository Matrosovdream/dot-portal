<?php

namespace App\Services\DummyData\Generators;

use App\Models\UserPaymentCard;
use App\Services\DummyData\AssignmentPicker;
use App\Services\DummyData\DummyConfig;
use Faker\Generator as Faker;

/**
 * Creates payment cards for each dummy company user. Runs before the
 * subscription generator so subscriptions can link a primary card.
 * Idempotent: keyed on (user_id, card_number).
 */
class PaymentGenerator
{
    public function __construct(
        private AssignmentPicker $picker,
        private Faker $faker,
    ) {}

    public function generate(): int
    {
        $count = 0;

        foreach ($this->picker->dummyCompanyUsers() as $company) {
            $num = $this->faker->numberBetween(...DummyConfig::CARDS_PER_COMPANY);

            for ($n = 1; $n <= $num; $n++) {
                $cardNumber = $this->faker->numerify('4###########');

                UserPaymentCard::updateOrCreate(
                    [
                        'user_id'     => $company->id,
                        'card_number' => $cardNumber,
                    ],
                    [
                        'card_holder_name'  => $company->firstname,
                        'expiry_date'       => $this->faker->numberBetween(1, 12) . '-' . $this->faker->numberBetween(2027, 2032),
                        'payment_method_id' => $this->picker->pick($this->picker->paymentMethodIds, 1),
                        'primary'           => $n === 1,
                    ],
                );

                $count++;
            }
        }

        return $count;
    }
}
