<?php

namespace App\Services\DummyData\Generators;

use App\Models\Vehicle;
use App\Services\DummyData\AssignmentPicker;
use App\Services\DummyData\DummyConfig;
use Faker\Generator as Faker;

/**
 * Creates vehicles per dummy company, each optionally assigned to one of that
 * company's drivers, with an MVR record and (when seeded) an insurance link.
 * Idempotent: keyed on a deterministic dummy unit number.
 */
class VehicleGenerator
{
    public function __construct(
        private AssignmentPicker $picker,
        private Faker $faker,
    ) {}

    public function generate(): int
    {
        $count = 0;

        foreach ($this->picker->dummyCompanyUsers() as $company) {
            $driverIds = $this->picker->driverIdsForCompany($company->id);
            $num = $this->faker->numberBetween(...DummyConfig::VEHICLES_PER_COMPANY);

            for ($n = 1; $n <= $num; $n++) {
                $number = "DUMMY-{$company->id}-{$n}";

                $vehicle = Vehicle::updateOrCreate(
                    ['number' => $number],
                    [
                        'unit_type_id'           => $this->picker->pick($this->picker->vehicleUnitTypeIds, 1),
                        'vin'                    => strtoupper($this->faker->bothify('?#?#####?#######')),
                        'ownership_type_id'      => $this->picker->pick($this->picker->vehicleOwnershipTypeIds, 1),
                        'driver_id'              => empty($driverIds) ? null : $this->faker->randomElement($driverIds),
                        'company_id'             => $company->id,
                        'company_user_id'        => $company->id,
                        'reg_expire_date'        => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
                        'inspection_expire_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                    ],
                );

                $vehicle->mvr()->updateOrCreate(
                    ['vehicle_id' => $vehicle->id],
                    [
                        'mvr_number' => 'MVR' . $this->faker->numerify('######'),
                        'mvr_date'   => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                    ],
                );

                if ($this->picker->insuranceId !== null) {
                    $vehicle->insurance()->delete();
                    $vehicle->insurance()->create(['insurance_id' => $this->picker->insuranceId]);
                }

                $count++;
            }
        }

        return $count;
    }
}
