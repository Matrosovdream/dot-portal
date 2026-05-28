<?php

namespace App\Actions\Api\V1\References;

use App\Repositories\References\RefCountryStateRepo;
use App\Repositories\References\RefDriverLicenseEndrsRepo;
use App\Repositories\References\RefDriverLicenseTypeRepo;
use App\Repositories\References\RefDriverTypeRepo;
use App\Repositories\References\RefOrderStatusRepo;
use App\Repositories\References\RefPaymentMethodRepo;
use App\Repositories\References\RefQueryPriceRepo;
use App\Repositories\References\RefRequestStatusRepo;
use App\Repositories\References\RefServiceGroupRepo;
use App\Repositories\References\RefVehicleOwnershipTypeRepo;
use App\Repositories\References\RefVehicleUnitTypeRepo;
use Illuminate\Support\Facades\Cache;

class ReferencesActions
{
    private const TTL = 3600;

    public function __construct(
        protected RefCountryStateRepo          $states,
        protected RefDriverTypeRepo            $driverTypes,
        protected RefDriverLicenseTypeRepo     $licenseTypes,
        protected RefDriverLicenseEndrsRepo    $licenseEndrs,
        protected RefVehicleOwnershipTypeRepo  $vehicleOwnership,
        protected RefVehicleUnitTypeRepo       $vehicleUnit,
        protected RefQueryPriceRepo            $queryPrices,
        protected RefPaymentMethodRepo         $paymentMethods,
        protected RefRequestStatusRepo         $requestStatuses,
        protected RefOrderStatusRepo           $orderStatuses,
        protected RefServiceGroupRepo          $serviceGroups,
    ) {}

    public function states()                { return $this->serve('states',           fn () => $this->states->getModel()::orderBy('name')->get()); }
    public function driverTypes()           { return $this->serve('driver-types',     fn () => $this->driverTypes->getModel()::orderBy('id')->get()); }
    public function licenseTypes()          { return $this->serve('license-types',    fn () => $this->licenseTypes->getModel()::orderBy('id')->get()); }
    public function licenseEndorsements()   { return $this->serve('license-endrs',    fn () => $this->licenseEndrs->getModel()::orderBy('id')->get()); }
    public function vehicleOwnershipTypes() { return $this->serve('vehicle-ownership',fn () => $this->vehicleOwnership->getModel()::orderBy('id')->get()); }
    public function vehicleUnitTypes()      { return $this->serve('vehicle-unit',     fn () => $this->vehicleUnit->getModel()::orderBy('id')->get()); }
    public function queryPrices()           { return $this->serve('query-prices',     fn () => $this->queryPrices->getModel()::orderBy('id')->get()); }
    public function paymentMethods()        { return $this->serve('payment-methods',  fn () => $this->paymentMethods->getModel()::orderBy('id')->get()); }
    public function requestStatuses()       { return $this->serve('request-statuses', fn () => $this->requestStatuses->getModel()::orderBy('id')->get()); }
    public function orderStatuses()         { return $this->serve('order-statuses',   fn () => $this->orderStatuses->getModel()::orderBy('id')->get()); }
    public function serviceGroups()         { return $this->serve('service-groups',   fn () => $this->serviceGroups->getModel()::orderBy('id')->get()); }

    public function bundle(): array
    {
        return Cache::remember('ref:bundle', self::TTL, function () {
            return [
                'states'                  => $this->states->getModel()::orderBy('name')->get(),
                'driver_types'            => $this->driverTypes->getModel()::orderBy('id')->get(),
                'license_types'           => $this->licenseTypes->getModel()::orderBy('id')->get(),
                'license_endorsements'    => $this->licenseEndrs->getModel()::orderBy('id')->get(),
                'vehicle_ownership_types' => $this->vehicleOwnership->getModel()::orderBy('id')->get(),
                'vehicle_unit_types'      => $this->vehicleUnit->getModel()::orderBy('id')->get(),
                'query_prices'            => $this->queryPrices->getModel()::orderBy('id')->get(),
                'payment_methods'         => $this->paymentMethods->getModel()::orderBy('id')->get(),
                'request_statuses'        => $this->requestStatuses->getModel()::orderBy('id')->get(),
                'order_statuses'          => $this->orderStatuses->getModel()::orderBy('id')->get(),
                'service_groups'          => $this->serviceGroups->getModel()::orderBy('id')->get(),
            ];
        });
    }

    private function serve(string $key, callable $producer)
    {
        return Cache::remember("ref:{$key}", self::TTL, $producer);
    }
}
