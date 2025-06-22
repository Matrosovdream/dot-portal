<?php

namespace App\Helpers\Expiration;

class ExpirationHelper
{

    public function getExpiredItems() {

        $expiredItems = [];
        $currentDate = now();
        $models = $this->mappedModels();

        foreach ($models as $key => $modelData) {
            $modelClass = $modelData['model'];
            $field = $modelData['field'];
            $margin = $modelData['margin'];

            // Fetch items that are expired or will expire within the margin
            $items = $modelClass::where($field, '<=', $currentDate->addDays($margin))->pluck('id')->toArray();

            if ( count($items) > 0 ) {
                $models[$key]['items'] = $items;
            }
        }

        return $expiredItems;

    }
    
    protected function mappedModels() {

        return [
            'driver_license' => [
                'title' => 'Driver license',
                'field' => 'expiration_date',
                'model' => 'App\Models\DriverLicense',
                'margin' => 30, // in days
            ],
            'driver_medical_card' => [
                'title' => 'Driver medical card',
                'field' => 'expiration_date',
                'model' => 'App\Models\DriverMedicalCard',
                'margin' => 30, // in days
            ],
            'vehicle_reg_expire_date' => [
                'title' => 'Vehicle registration expiration date',
                'field' => 'reg_expire_date',
                'model' => 'App\Models\Vehicle',
                'margin' => 30, // in days
            ],
            'vehicle_inspection_expire_date' => [
                'title' => 'Vehicle inspection expiration date',
                'field' => 'inspection_expire_date',
                'model' => 'App\Models\Vehicle',
                'margin' => 30, // in days
            ],
            'insurance_vehicle' => [
                'title' => 'Vehicle insurance',
                'field' => 'end_date',
                'model' => 'App\Models\InsuranceVehicle',
                'margin' => 30, // in days
            ],
        ];

    }

}