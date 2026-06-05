<?php

namespace App\Services\DummyData\Generators;

use App\Models\User;
use App\Services\DummyData\AssignmentPicker;
use App\Services\DummyData\DummyConfig;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/**
 * Creates company accounts (User role=company + UserCompany profile).
 *
 * The first records reuse curated "anchor" companies from
 * tests/DummyData/dotportal/companies.json for stable, realistic demos; any
 * remaining companies are generated with Faker. Idempotent: keyed on the dummy
 * email, so re-running updates rather than duplicates.
 */
class CompanyUserGenerator
{
    public function __construct(
        private AssignmentPicker $picker,
        private Faker $faker,
    ) {}

    public function generate(int $companies): int
    {
        $anchors = $this->loadAnchors();
        $count = 0;

        for ($i = 1; $i <= $companies; $i++) {
            $email   = DummyConfig::companyEmail($i);
            $anchor  = $anchors[$i - 1] ?? null;
            $profile = $anchor ?? $this->fakeCompany();

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'firstname' => $profile['name'],
                    'phone'     => $profile['phone'],
                    'password'  => Hash::make(DummyConfig::PASSWORD),
                    'is_active' => 1,
                ],
            );

            $user->roles()->sync([DummyConfig::ROLE_COMPANY]);

            $user->company()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name'           => $profile['name'],
                    'phone'          => $profile['phone'],
                    'dot_number'     => $profile['dot_number'],
                    'mc_number'      => $profile['mc_number'] ?? '',
                    'trucks_number'  => $profile['trucks_number'] ?? null,
                    'drivers_number' => $profile['drivers_number'] ?? null,
                ],
            );

            $count++;
        }

        return $count;
    }

    private function fakeCompany(): array
    {
        $suffix = $this->faker->randomElement(['LLC', 'Inc', 'Carriers', 'Transport', 'Logistics', 'Trucking Co']);

        return [
            'name'           => $this->faker->lastName() . ' ' . $suffix,
            'phone'          => $this->faker->numerify('+1-###-555-####'),
            'dot_number'     => (string) $this->faker->numberBetween(1_000_000, 4_999_999),
            'mc_number'      => (string) $this->faker->numberBetween(100_000, 1_499_999),
            'trucks_number'  => $this->faker->numberBetween(2, 50),
            'drivers_number' => $this->faker->numberBetween(1, 45),
        ];
    }

    /** @return array<int,array<string,mixed>> */
    private function loadAnchors(): array
    {
        $path = base_path('tests/DummyData/dotportal/companies.json');

        if (!is_file($path)) {
            return [];
        }

        $decoded = json_decode(file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }
}
