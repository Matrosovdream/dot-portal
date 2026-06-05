<?php

namespace App\Services\DummyData\Generators;

use App\Repositories\Request\RequestRepo;
use App\Services\DummyData\AssignmentPicker;
use App\Services\DummyData\DummyConfig;
use Faker\Generator as Faker;

/**
 * Creates service requests for each dummy company against the seeded services
 * catalogue, each with a single history entry. Requires seeded services and
 * request statuses; skips gracefully when either is missing.
 */
class ServiceRequestGenerator
{
    private RequestRepo $requestRepo;

    public function __construct(
        private AssignmentPicker $picker,
        private Faker $faker,
    ) {
        $this->requestRepo = new RequestRepo();
    }

    public function generate(): int
    {
        if ($this->picker->services->isEmpty() || empty($this->picker->requestStatusIds)) {
            return 0;
        }

        $count = 0;

        foreach ($this->picker->dummyCompanyUsers() as $company) {
            $num = $this->faker->numberBetween(...DummyConfig::REQUESTS_PER_COMPANY);

            for ($n = 1; $n <= $num; $n++) {
                $service  = $this->picker->services->random();
                $statusId = $this->picker->pick($this->picker->requestStatusIds, 1);
                $isPaid   = (bool) $service->is_paid;
                $price    = $isPaid ? (float) $service->price : 0;

                $request = $this->requestRepo->create([
                    'user_id'        => $company->id,
                    'status_id'      => $statusId,
                    'service_id'     => $service->id,
                    'is_paid'        => $isPaid ? $this->faker->boolean(70) : false,
                    'price'          => $price,
                    'discount_price' => 0,
                ]);

                if (!isset($request['Model'])) {
                    continue;
                }

                $request['Model']->history()->create([
                    'status_id' => $statusId,
                    'user_id'   => $company->id,
                    'comment'   => 'Request created (dummy data)',
                ]);

                $count++;
            }
        }

        return $count;
    }
}
