<?php

namespace App\Helpers\Validation\Models;


class VehicleValidation extends AbstractValidation {

    public function validateAll() {

        $sections = [
            
        ];

        return $this->checkSections($sections);

    }

    // We set all required fields here
    public function getFields() {

        return [
            
        ];

    }

}