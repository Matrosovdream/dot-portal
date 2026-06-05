<?php

namespace App\Services\DummyData;

use App\Models\Driver;
use App\Models\InsuranceVehicle;
use App\Models\RefCountryStates;
use App\Models\RefDriverLicenseEndrs;
use App\Models\RefDriverLicenseType;
use App\Models\RefDriverType;
use App\Models\RefOrderStatus;
use App\Models\RefPaymentMethod;
use App\Models\RefRequestStatus;
use App\Models\RefVehicleOwnershipType;
use App\Models\RefVehicleUnitType;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Loads the seeded reference pools once and hands generators valid ids to wire
 * dummy business records onto. Mirrors the Freshdesk AssignmentPicker, which
 * pulled existing users/roles so imported data attached to real accounts.
 */
class AssignmentPicker
{
    /** @var array<int,int> */
    public array $driverTypeIds;
    /** @var array<int,int> */
    public array $licenseTypeIds;
    /** @var array<int,int> */
    public array $licenseEndrsIds;
    /** @var array<int,int> */
    public array $stateIds;
    /** @var array<int,int> */
    public array $vehicleUnitTypeIds;
    /** @var array<int,int> */
    public array $vehicleOwnershipTypeIds;
    /** @var array<int,int> */
    public array $orderStatusIds;
    /** @var array<int,int> */
    public array $requestStatusIds;
    /** @var array<int,int> */
    public array $paymentMethodIds;

    public Collection $services;
    public Collection $subscriptions;
    public ?int $insuranceId;

    public function __construct()
    {
        $this->driverTypeIds           = RefDriverType::query()->pluck('id')->all();
        $this->licenseTypeIds          = RefDriverLicenseType::query()->pluck('id')->all();
        $this->licenseEndrsIds         = RefDriverLicenseEndrs::query()->pluck('id')->all();
        $this->stateIds                = RefCountryStates::query()->pluck('id')->all();
        $this->vehicleUnitTypeIds      = RefVehicleUnitType::query()->pluck('id')->all();
        $this->vehicleOwnershipTypeIds = RefVehicleOwnershipType::query()->pluck('id')->all();
        $this->orderStatusIds          = RefOrderStatus::query()->pluck('id')->all();
        $this->requestStatusIds        = RefRequestStatus::query()->pluck('id')->all();
        $this->paymentMethodIds        = RefPaymentMethod::query()->pluck('id')->all();

        $this->services      = Service::query()->get(['id', 'name', 'slug', 'is_paid', 'price']);
        $this->subscriptions = Subscription::query()->get(
            ['id', 'name', 'price_per_driver', 'drivers_amount_from', 'drivers_amount_to']
        );

        $this->insuranceId = InsuranceVehicle::query()->value('id');
    }

    /** Pick a random id from a pool, or fall back when the reference is empty. */
    public function pick(array $pool, $default = null)
    {
        return empty($pool) ? $default : $pool[array_rand($pool)];
    }

    /** Company users we created previously (role = company, dummy email domain). */
    public function dummyCompanyUsers(): Collection
    {
        return User::query()
            ->where('email', 'like', DummyConfig::emailLikePattern())
            ->whereHas('roles', fn ($q) => $q->where('roles.id', DummyConfig::ROLE_COMPANY))
            ->orderBy('id')
            ->get();
    }

    /** Driver ids belonging to a given dummy company user. */
    public function driverIdsForCompany(int $companyUserId): array
    {
        return Driver::query()
            ->where('company_user_id', $companyUserId)
            ->pluck('id')
            ->all();
    }

    /** Choose the subscription plan whose driver band fits the fleet size. */
    public function subscriptionForDrivers(int $drivers)
    {
        if ($this->subscriptions->isEmpty()) {
            return null;
        }

        $match = $this->subscriptions->first(function ($s) use ($drivers) {
            return $drivers >= (int) $s->drivers_amount_from
                && $drivers <= (int) $s->drivers_amount_to;
        });

        return $match ?? $this->subscriptions->first();
    }
}
