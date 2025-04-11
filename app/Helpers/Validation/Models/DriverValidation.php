<?php

namespace App\Helpers\Validation\Models;


class DriverValidation {

    public $data = [];

    public function __construct( $data = [] ) {
        $this->data = $data;
    }

    public function validateAll() {

        $data = $this->data;

        return [
            'general' => $this->validateGeneral(),
        ];

    }

    public function validateGeneral() {

        $data = $this->data;
        $fields = $this->getFields()['general'];

        return $this->checkValidation($data, $fields);

    }

    public function checkValidation($data, $fields) {

        $errors = [];

        foreach ($fields as $key => $field) {
            if ($field['required'] && empty($data[$key])) {
                $errors[$key] = $field['title'] . ' is required.';
            }
        }

        return $errors;

    }

    public function setData($data) {
        $this->data = $data;
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