<?php

namespace App\Helpers\Validation\Models;

class AbstractValidation
{
    public $data = [];

    public function __construct( $data = [] ) {
        $this->data = $data;
    }

    public function getValidationResult($data, $fields) {
        $errors = $this->checkFields($data, $fields);

        return [
            'valid' => empty($errors) ? true : false,
            'errors' => $errors,
        ];
    }

    public function checkFields($data, $fields) {

        $errors = [];

        foreach ($fields as $key => $field) {
            if ($field['required'] && empty($data[$key])) {
                $errors[$key] = $field;
            }
        }

        return $errors;

    }

    public function checkSections( $sections ) {

        $valid = true;
        $errors = [];

        foreach ($sections as $section => $result) {
            if (!$result['valid']) {
                $valid = false;
                $errors[$section] = $result['errors'];
            }
        }

        return [
            'valid' => $valid,
            'errors' => $errors,
        ];

    }

    public function validateWithData( $data, $fields ) {

        return $this->getValidationResult(
            $this->data, 
            $fields ?? []
        );

    }

    public function setData($data) {
        $this->data = $data;
        return $this; // To make calls chainable
    }

}