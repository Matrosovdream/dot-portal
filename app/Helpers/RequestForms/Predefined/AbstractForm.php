<?php

namespace App\Helpers\RequestForms\Predefined;

use App\Repositories\References\RefCountryStateRepo;
use App\Repositories\Vehicle\VehicleRepo;

class AbstractForm
{

    protected $formName = '';
    protected $formTitle = '';
    protected $formDescription = '';
    protected $formFields = [];

    /**
     * Get the references for the form fields.
     *
     * @return array
    */
    public function getReferences()
    {
        return [];
    }

    /**
     * Get the form data.
     *
     * @return array
    */
    public function getFormData()
    {
        return [
            'formName' => $this->formName,
            'formTitle' => $this->formTitle,
            'formDescription' => $this->formDescription,
            'formFields' => $this->formFields,
        ];
    }

    protected function getCountryStates()
    {
        $statesRepo = new RefCountryStateRepo();
        $states = $statesRepo->getAll();

        $list = [];
        foreach ($states['items'] as $state) {
            $list[] = [
                'value' => $state['id'],
                'title' => $state['name']
            ];
        }

        return $list;

    }

    protected function getVehicles($filter = [])
    {
        $vehicles = new VehicleRepo();

        $vehiclesList = $vehicles->getAll($filter, 1000);
        
        $list = [];
        foreach ($vehiclesList['items'] as $vehicle) {
            $list[] = [
                'value' => $vehicle['id'],
                'title' => "VIN #".$vehicle['vin']
            ];      
        }

        return $list;
        
    }

    protected function getDrivers($filter = [])
    {
        $drivers = new \App\Repositories\Driver\DriverRepo();

        $driversList = $drivers->getAll($filter, 1000);

        $list = [];
        foreach ($driversList['items'] as $driver) {
            $list[] = [
                'value' => $driver['id'],
                'title' => $driver['firstname'].' '.$driver['lastname']
            ];      
        }

        return $list;
        
    }

    protected function getQuarterPeriods()
    {
        return [
            ['value' => '1', 'title' => 'January - March'],
            ['value' => '2', 'title' => 'April - June'],
            ['value' => '3', 'title' => 'July - September'],
            ['value' => '4', 'title' => 'October - December'],
        ];
    }

}