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
            if ( 
                $field['required'] && 
                ( empty($data[$key]) || $data[$key] === null )
                ) {
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

    // Func calc completion percent go through all sections and calculate the percent of completion
    public function calcPercent( $sections=[] ) {

        if (empty($sections)) {
            return 0;
        }

        $totalSections = count($sections);
        $completedSections = 0;

        foreach ($sections as $section => $result) {
            if ($result['valid']) {
                $completedSections++;
            }
        }

        return round( ($completedSections / $totalSections) * 100 );

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

    private function getFields() {
        // This method should be implemented in the child classes to return the fields for validation
        return [];
    }

    private function getTabs() {
        // This method should be implemented in the child classes to return the tabs for validation
        return [];
    }

}