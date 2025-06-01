<?php
namespace App\References;

class FormFieldReferences {

    public function getTypes() {

        return [
            'text' => [
                'type'=> 'text',
                'title' => 'Text',
            ],
            'date' => [
                'type'=> 'date',
                'title' => 'Date',
            ],
            'textarea' => [
                'type'=> 'textarea',
                'title' => 'Textarea',
            ],
            'phone' => [
                'type'=> 'phone',
                'title' => 'Phone',
            ],
            'file' => [
                'type'=> 'file',
                'title' => 'File',
            ],
            /*
            'reference' => [
                'type'=> 'reference',
                'title' => 'Reference',
            ],
            */
        ];

    }

}