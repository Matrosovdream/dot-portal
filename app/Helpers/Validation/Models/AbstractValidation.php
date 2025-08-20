<?php

namespace App\Helpers\Validation\Models;

use App\Repositories\User\UserTaskRepo;

class AbstractValidation
{
    public $data = [];
    public $entity = '';

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

    public function getData() {
        return $this->data ?? null;
    }

    private function getFields() {
        // This method should be implemented in the child classes to return the fields for validation
        return [];
    }

    private function getTabs() {
        // This method should be implemented in the child classes to return the tabs for validation
        return [];
    }

    public function setDataModel($item_id): void {}

    public function updateUserTasks(int $item_id): void
    {

        $userTaskRepo = app(UserTaskRepo::class);

        // Set data model based on item_id
        $this->setDataModel($item_id);

        // Get data from the class
        $data = $this->getData();
        if (!$data) {
            return; // Handle case where driver data is not found
        }
        
        // Set data and validate
        $validRes = $this->setData($data)->validateAll();
        $errors = $validRes['errors'] ?? null;
        $tabs = $validRes['tabs'] ?? null;

        //dd($validRes);

        if( $errors && $tabs) {

            // Match for missing tabs and deactivate respective tasks
            $closedTabs = [];
            foreach( $tabs as $tab => $tabItem ) {
                if( !isset( $errors[$tab] ) ) { $closedTabs[] = $tab; }
            }    

            $res = $userTaskRepo->model
            ->where(
                [
                    'entity_id' => $data['id'],
                    'entity' => $this->entity
                ]
            )->whereIn('tab',$closedTabs)
            ->update(
                [
                    'status' => 'closed',
                ]
            );

        }
    }

}