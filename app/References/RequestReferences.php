<?php
namespace App\References;

class RequestReferences {

    public function getRequestStatuses() {

        return [
            ['name' => 'Pending', 'slug' => 'pending', 'color' => 'blue', 'published' => 1],
            ['name' => 'Consent Not Given', 'slug' => 'consent-not-given', 'color' => 'gray', 'published' => 1],
            ['name' => 'Accepted / Completed', 'slug' => 'accepted-completed', 'color' => 'green', 'published' => 1],
            ['name' => 'Passed / No Record', 'slug' => 'passed-no-record', 'color' => 'green', 'published' => 1],
            ['name' => 'Violation Found', 'slug' => 'violation-found', 'color' => 'red', 'published' => 1],
        ];
        
    }

}