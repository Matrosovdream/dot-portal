<?php

namespace App\Helpers\Validation\Models;


class DriverValidation extends AbstractValidation {

    public function validateAll() {

        $sections = [
            'general' => $this->validateGeneral(),
            'license' => $this->validateLicense(),
            'address' => $this->validateAddress(),
            'medicalCard' => $this->validateMedicalCard(),
            'drugTest' => $this->validateDrugTest(),
        ];

        return array_merge(
            $this->checkSections($sections),
            [
                'percent' => $this->calcPercent($sections),
            ]
        );
    }
    public function validateGeneral() {

        return $this->getValidationResult(
            $this->data, 
            $this->getFields()['general'] ?? []
        );

    }

    public function validateLicense() {

        return $this->getValidationResult(
            $this->data['license'] ?? [], 
            $this->getFields()['license'] ?? []
        );

    }

    public function validateAddress() {

        return $this->getValidationResult(
            $this->data['address'] ?? [], 
            $this->getFields()['address'] ?? []
        );

    }

    public function validateMedicalCard() {

        return $this->getValidationResult(
            $this->data['medicalCard'] ?? [], 
            $this->getFields()['medical_card'] ?? []
        );

    }

    public function validateDrugTest() {

        return $this->getValidationResult(
            $this->data['drugTest'] ?? [], 
            $this->getFields()['drug_test'] ?? []
        );

    }

    // We set all required fields here
    public function getFields() {

        // General
        $general = [
            'firstname' => ['title' => 'First Name', 'required' => true],
            'lastname' => ['title' => 'Last Name', 'required' => true],
            'dob' => ['title' => 'DOB', 'required' => true],
            'ssn' => ['title' => 'SSN', 'required' => true],
            'hire_date' => ['title' => 'Hire Date', 'required' => true],
            'driver_type_id' => ['title' => 'Driver Type', 'required' => true],
        ];

        // License
        $license = [
            'type_id' => ['title' => 'License Type', 'required' => true],
            'endorsement_id' => ['title' => 'License Endorsement', 'required' => true],
            'license_number' => ['title' => 'License Number', 'required' => true],
            'expiration_date' => ['title' => 'License Expiration Date', 'required' => true],
            'state_id' => ['title' => 'License State', 'required' => true],
            //'document_id' => ['title' => 'License Document', 'required' => true],
        ];

        // Address
        $address = [
            'address1' => ['title' => 'Address 1', 'required' => true],
            'address2' => ['title' => 'Address 2', 'required' => false],
            'city' => ['title' => 'City', 'required' => true],
            'state_id' => ['title' => 'State', 'required' => true],
            'zip' => ['title' => 'Zip', 'required' => true],
        ];

        // Medical card
        $medical_card = [
            'examiner_name' => ['title' => 'Examiner Name', 'required' => true],
            'national_registry' => ['title' => 'National Registry Number', 'required' => true],
            'issue_date' => ['title' => 'Issue Date', 'required' => true],
            'expiration_date' => ['title' => 'Expiration Date', 'required' => true],
        ];

        // Drug test
        $drug_test = [
            'test_date' => ['title' => 'Test Date', 'required' => true],
            'file_id' => ['title' => 'Drug Test Document', 'required' => true],
        ];

        return [
            'general' => $general,
            'license' => $license,
            'address' => $address,
            'medical_card' => $medical_card,
            'drug_test' => $drug_test,
        ];

    }

}