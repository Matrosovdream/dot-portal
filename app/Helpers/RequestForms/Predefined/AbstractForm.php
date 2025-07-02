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

    /**
     * Validate the request form data
     *
     * @return array
    */
    public function validateFormData( $requestData )
    {
        $errors = [];

        // Check required fields
        foreach ($this->formFields as $field => $options) {
            if (
                isset($options['required']) && 
                $options['required'] && 
                empty($requestData[$field])
                ) {
                $errors[$field] = 'The '.$options['label'].' field is required.';
            }
        }

        return $errors;
    }

    /**
     * Get the form fields.
     *
     * @return array
    */
    public function getFormFields()
    {   
        return $this->formFields;
    }

    /**
     * Match the field values with the request data.
     *
     * @param array $requestData
     * @return array
    */
    public function matchFieldValues( $values )
    {

        $fields = $this->getFormFields();
        $references = $this->getReferences();
        $values = $values['Mapped'];

        foreach ( $fields as $slug => $field ) {

            $value = '';

            if ( isset($values[$slug]) ) {
                $value = $values[$slug];
            } else {
                $value = '';
            }

            // If it's a reference field then set value
            if( isset($field['reference']) ) {
                $options = $references[ $field['reference'] ] ?? null;

                $fields[$slug]['valueRef'] = $value;
                $value = $options['options'][$value]['title'] ?? null;
                
            }
            
            $fields[$slug]['value'] = $value;

        } 

        return $fields;

    }

    /**
     * Prepare references for the form fields.
     *
     * @param array $refs
     * @return array
    */
    protected function prepareReferences( $refs )
    {

        // Loop throug each item, take options loop and set key as value
        foreach ($refs as $key => $ref) {
            if (isset($ref['options']) && is_array($ref['options'])) {

                $newOptions = [];
                foreach ($ref['options'] as $option) {
                    $newOptions[$option['value']] = $option;
                }
                $refs[$key]['options'] = $newOptions;

            }
        }

        return $refs;
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