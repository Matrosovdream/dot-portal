<?php

namespace App\Services\DummyData\Generators;

use App\Repositories\Driver\DriverRepo;
use App\Services\DummyData\AssignmentPicker;
use App\Services\DummyData\DummyConfig;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/**
 * Creates drivers for each dummy company, including the driver login account.
 *
 * Goes through DriverRepo::create() (the same path the app/UI uses), which
 * creates the underlying User, assigns the driver role and links it back to the
 * company. Address, license and medical card are attached via the model
 * relations, matching DriverSeeder.
 */
class DriverGenerator
{
    private DriverRepo $driverRepo;

    public function __construct(
        private AssignmentPicker $picker,
        private Faker $faker,
    ) {
        $this->driverRepo = new DriverRepo();
    }

    public function generate(): int
    {
        $count = 0;

        foreach ($this->picker->dummyCompanyUsers() as $company) {
            $num = $this->faker->numberBetween(...DummyConfig::DRIVERS_PER_COMPANY);

            for ($n = 1; $n <= $num; $n++) {
                $email = DummyConfig::driverEmail($company->id, $n);

                $result = $this->driverRepo->create([
                    'firstname'      => $this->faker->firstName(),
                    'lastname'       => $this->faker->lastName(),
                    'phone'          => $this->faker->numerify('##########'),
                    'email'          => $email,
                    'password'       => Hash::make(DummyConfig::PASSWORD),
                    'dob'            => $this->faker->date('Y-m-d', '2002-01-01'),
                    'ssn'            => $this->faker->numerify('#########'),
                    'hire_date'      => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
                    'driver_type_id' => $this->picker->pick($this->picker->driverTypeIds, 1),
                    'company_id'     => $company->id,
                    'company_user_id' => $company->id,
                    'status_id'      => $this->faker->randomElement([1, 1, 1, 2, 3]),
                ]);

                // Email already taken (e.g. re-run without --fresh): skip cleanly.
                if (isset($result['error'])) {
                    continue;
                }

                $driver = $result['Model'];

                $driver->address()->create([
                    'address1' => $this->faker->streetAddress(),
                    'address2' => $this->faker->optional()->secondaryAddress(),
                    'city'     => $this->faker->city(),
                    'state_id' => $this->picker->pick($this->picker->stateIds, 1),
                    'zip'      => $this->faker->postcode(),
                ]);

                $driver->license()->create([
                    'type_id'         => $this->picker->pick($this->picker->licenseTypeIds, 1),
                    'endorsement_id'  => $this->picker->pick($this->picker->licenseEndrsIds, 1),
                    'license_number'  => strtoupper($this->faker->bothify('?#######')),
                    'expiration_date' => $this->faker->dateTimeBetween('now', '+4 years')->format('Y-m-d'),
                    'state_id'        => $this->picker->pick($this->picker->stateIds, 1),
                ]);

                $driver->medicalCard()->create([
                    'examiner_name'     => 'Dr. ' . $this->faker->lastName(),
                    'national_registry' => $this->faker->numerify('##########'),
                    'issue_date'        => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                    'expiration_date'   => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
                ]);

                $count++;
            }
        }

        return $count;
    }
}
