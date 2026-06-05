<?php

namespace App\Services\DummyData\Generators;

use App\Models\Order;
use App\Repositories\Order\OrderRepo;
use App\Services\DummyData\AssignmentPicker;
use App\Services\DummyData\DummyConfig;
use Faker\Generator as Faker;

/**
 * Creates orders (with line items and a payment) for each dummy company via
 * OrderRepo::createWithPayload(). Orders have no natural key, so idempotency is
 * count-based: each run tops a company up to its target order count rather than
 * appending duplicates.
 */
class OrderGenerator
{
    private OrderRepo $orderRepo;

    public function __construct(
        private AssignmentPicker $picker,
        private Faker $faker,
    ) {
        $this->orderRepo = new OrderRepo();
    }

    public function generate(): int
    {
        $count = 0;

        foreach ($this->picker->dummyCompanyUsers() as $company) {
            $target   = $this->faker->numberBetween(...DummyConfig::ORDERS_PER_COMPANY);
            $existing = Order::query()->where('user_id', $company->id)->count();

            for ($n = $existing; $n < $target; $n++) {
                $items  = $this->buildItems();
                $amount = array_sum(array_map(fn ($i) => $i['quantity'] * $i['price'], $items));

                $order = $this->orderRepo->createWithPayload([
                    'user_id'           => $company->id,
                    'amount'            => $amount,
                    'discount_amount'   => 0,
                    'status_id'         => $this->picker->pick($this->picker->orderStatusIds, 1),
                    'payment_method_id' => $this->picker->pick($this->picker->paymentMethodIds, 1),
                    'notes'             => 'Generated dummy order',
                    'items'             => $items,
                ]);

                if (is_array($order) && isset($order['Model'])) {
                    $order['Model']->payments()->create([
                        'payment_method_id' => $this->picker->pick($this->picker->paymentMethodIds, 1),
                        'amount'            => $amount,
                        'status'            => $this->faker->randomElement(['success', 'success', 'pending']),
                        'transaction_id'    => strtoupper($this->faker->bothify('TXN-########')),
                        'payment_date'      => now()->subDays($this->faker->numberBetween(0, 60))->toDateTimeString(),
                    ]);
                }

                $count++;
            }
        }

        return $count;
    }

    /** @return array<int,array<string,mixed>> */
    private function buildItems(): array
    {
        $items = [];
        $lines = $this->faker->numberBetween(1, 3);

        for ($i = 0; $i < $lines; $i++) {
            $service = $this->picker->services->isNotEmpty() ? $this->picker->services->random() : null;
            $price   = $service && $service->price > 0
                ? (float) $service->price
                : (float) $this->faker->numberBetween(25, 400);

            $items[] = [
                'item_name'        => $service->name ?? 'Service fee',
                'item_description' => 'Dummy order line',
                'entity'           => 'service',
                'entity_id'        => $service->id ?? 0,
                'quantity'         => 1,
                // OrderRepo reads 'price' for unit_price and 'unit_price' for the
                // line total; pass both equal so the two stay consistent.
                'price'            => $price,
                'unit_price'       => $price,
            ];
        }

        return $items;
    }
}
