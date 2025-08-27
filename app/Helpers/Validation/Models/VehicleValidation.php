<?php

namespace App\Helpers\Validation\Models;

use App\Repositories\Vehicle\VehicleRepo;


class VehicleValidation extends AbstractValidation {

    public $entity = 'vehicle';
    public $repoClass = VehicleRepo::class;

    public function validateAll() {

        $sections = [
            'general' => $this->validateGeneral(),
            //'mvr' => $this->validateMvr(),
            'insurance' => $this->validateInsurance(),
        ];

        return array_merge(
            $this->checkSections($sections),
            [
                'percent' => $this->calcPercent($sections),
                'tabs' => $this->getTabs()
            ]
        );

    }

    public function validateGeneral() {

        return $this->getValidationResult(
            $this->data, 
            $this->getFields()['general'] ?? []
        );

    }

    public function validateMvr() {

        return $this->getValidationResult(
            $this->data['mvr'] ?? [], 
            $this->getFields()['mvr'] ?? []
        );

    }

    public function validateInsurance() {

        return $this->getValidationResult(
            $this->data['insurance'] ?? [], 
            $this->getFields()['insurance'] ?? []
        );

    }

    // We set all required fields here
    public function getFields() {

        // General
        $general = [
            'number' => ['title' => 'Number', 'required' => true],
            'vin' => ['title' => 'VIN', 'required' => true],
            'unit_type_id' => ['title' => 'Unit type ID', 'required' => true],
            'ownership_type_id' => ['title' => 'Ownership type ID', 'required' => true],
            'driver_id' => ['title' => 'Driver', 'required' => true],
            'reg_expire_date' => ['title' => 'Registration expire date', 'required' => true],
            'inspection_expire_date' => ['title' => 'Inspection expire date', 'required' => true],
        ];

        // MVR
        /*$mvr = [
            'mvr_number' => ['title' => 'MVR number', 'required' => true],
            'mvr_date' => ['title' => 'MVR date', 'required' => true],
            'file_id' => ['title' => 'Document', 'required' => true],
        ];*/

        // Insurance
        $insurance = [
            'id' => ['title' => 'Insurance ID', 'required' => true],
        ];

        return [
            'general' => $general,
            //'mvr' => $mvr,
            'insurance' => $insurance,
        ];

    }

    private function getTabs() {

        return [
            'general' => [
                'title' => 'General',
            ],
            'mvr' => [
                'title' => 'MVR',
            ],
            'insurance' => [
                'title' => 'Insurance',
            ],
        ];

    }

    public function setDataModel( $item_id ): void
    {
        $vehicleRepo = app( $this->repoClass );
        $this->setData( $vehicleRepo->getByID($item_id) );
    }

}