<?php

namespace App\Helpers\Validation\Models;


class DriverValidation extends AbstractValidation {

    public function validateAll() {

        return [
            'general' => $this->validateGeneral(),
        ];

    }
    public function validateGeneral() {

        return $this->getValidationResult(
            $this->data, 
            $this->getFields()['general']
        );

    }

    // We set all required fields here
    public function getFields() {

        $general = [
            'first_name' => ['title' => 'First Name', 'required' => true],
            'last_name' => ['title' => 'Last Name', 'required' => true],
            'dob' => ['title' => 'DOB', 'required' => true],
            'ssn' => ['title' => 'SSN', 'required' => true],
            'hire_date' => ['title' => 'Hire Date', 'required' => true],
        ];

        return [
            'general' => $general
        ];

    }

}