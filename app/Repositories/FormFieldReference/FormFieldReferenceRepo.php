<?php
namespace App\Repositories\FormFieldReference;

use App\Models\ReferenceFormField;
use App\Models\ProductFieldReference;
use App\Models\Country;
use App\Models\Airport;
use App\Helpers\TravellerHelper;


class FormFieldReferenceRepo
{

    protected $model;

    protected $fields = [
        'title',
        'slug',
        'entity',
        'type',
        'section',
        'placeholder',
        'tooltip',
        'description',
        'default_value',
        'reference_code',
        'default',
        'icon',
        'is_email',
        'is_phone',
        'is_fullname',
    ];

    public function __construct()
    {
        $this->model = new ReferenceFormField();
    }

    public function getInitialFields($filters = [])
    {

        $query = ReferenceFormField::orderBy('id', 'asc');
        if (count($filters) > 0) {

            foreach ($filters as $key => $value) {
                $query->where($key, $value);
            }

        }

        $fields = $query->get();

        // Prepare the fields
        $data = [];
        foreach ($fields as $field) {
            $data[] = $this->prepareInitialField( $field );
        }

        return $data;

    }

    public function getOrderFields()
    {
        return $this->getInitialFields(['entity' => 'order']);
    }

    public function getTravellerFields()
    {
        return $this->getInitialFields(['entity' => 'traveller']);
    }

    public function getById($id)
    {

        $data = ReferenceFormField::find($id);

        $fields = $this->prepareInitialField( $data );

        if ($data->reference_code) {
            $fields['options'] = $this->getRefererenceOptions($data->reference_code);
        }

        return $fields;

    }

    public function getProductFields($product_id, $filters = [])
    {

        $query = ProductFieldReference::orderBy('id', 'asc');

        // Filter by product_id
        $query->where('product_id', $product_id);

        // Apply additional filters
        if (count($filters) > 0) {

            foreach ($filters as $key => $value) {
                $query->where($key, $value);
            }

        }

        // Get the results
        $fields = $query->get();

        // Prepare the fields
        $data = [];
        foreach ($fields as $field) {

            $fieldData = $this->getById($field->field_id);

            if( $fieldData['type'] == 'reference' ) {
                //$fieldData['type'] = 'select';
            }

            $data[] = [
                "id" => $fieldData['id'],
                "entity" => $field->entity,
                "section" => $field->section,
                "required" => $field->required,
                "default_value" => $field->default_value,
                "type" => $fieldData['type'],
                "slug" => $fieldData['slug'],
                "title" => $fieldData['title'],
                "value" => null,
                "placeholder" => $fieldData['placeholder'],
                'classes' => $field->classes,
                "options" => $fieldData['options'] ?? [],
                "field" => $fieldData
            ];
        }

        //dd($data);

        return $data;

    }

    public function getRefererenceOptions($reference_code)
    {

        switch ($reference_code) {
            case 'country':
                $options = Country::all()->toArray();
                break;
            case 'airport':
                $options = $this->getAirportsReference();
                break;
            case 'gender':
                $options = TravellerHelper::getReference('gender');
                break;
            case 'boolean':
                $options = TravellerHelper::getReference('boolean');
                break;
            case 'marital_status':
                $options = TravellerHelper::getReference('marital_status');
                break;
            default:
                $options = [];
        }

        return $options;

    }

    public function getReferences( $with_options = false ) {

        $data = [
            'country' => ['title' => 'Country'],
            'airport' => ['title' => 'Airport'],
            'gender'  => ['title' => 'Gender'],
            'boolean' => ['title' => 'Boolean'],
            'marital_status' => ['title' => 'Marital Status'],
        ];

        if( $with_options ) {

            foreach( $data as $key => $value ) {
                $data[ $key ]['options'] = $this->getRefererenceOptions( $key );
            }

        }

        return $data;

    }

    public function getEntities() {   
        return [
            'order' => ['title' => 'Order'],
            'traveller' => ['title' => 'Traveller'],
        ];
    }

    public function getFieldTypes() {
        return [
            'text' => ['title' => 'Text'],
            'date' => ['title' => 'Date'],
            'textarea' => ['title' => 'Textarea'],
            'reference' => ['title' => 'Reference'],
        ];
    }

    public function getFieldSections() {
        $data = [
            'trip' => ['title' => 'Trip'],
            'personal' => ['title' => 'Personal'],
            'passport' => ['title' => 'Passport'],
            'family' => ['title' => 'Family'],
            'past_travel' => ['title' => 'Past Travel'],
            'declarations' => ['title' => 'Declarations'],
        ];
        return $data;
    }

    public function getOrderSections() {
        return [
            'trip' => ['title' => 'Trip'],
        ];
    }

    public function getTravellerSections() {
        return [
            'personal' => ['title' => 'Personal'],
            'passport' => ['title' => 'Passport'],
            'family' => ['title' => 'Family'],
            'past_travel' => ['title' => 'Past Travel'],
            'declarations' => ['title' => 'Declarations'],
        ];
    }

    public function getAirportsReference() {

        $items = Airport::all();

        $data = [];
        foreach( $items as $item ) {

            $data[ $item->id ] = [
                "id" => $item->id,
                "title" => $item->name,
                "iso_country" => $item->iso_country,
            ];

        }

        return $data;

    }

    private function prepareInitialField($field)
    {

        return [
            "id" => $field->id,
            "title" => $field->title,
            "slug" => $field->slug,
            "entity" => $field->entity,
            "type" => $field->type,
            "section" => $field->section,
            "placeholder" => $field->placeholder,
            "tooltip" => $field->tooltip,
            "description" => $field->description,
            "default_value" => $field->default_value,
            "reference_code" => $field->reference_code,
            "default" => $field->default,
            "required" => $field->required,
            "value" => null,
            "icon" => $field->icon,
            "is_email" => $field->is_email,
            "is_phone" => $field->is_phone,
            "is_fullname" => $field->is_fullname,
            "classes" => $field->classes,
        ];

    }

    public function create($data)
    {

        // Prepare the values
        $values = $this->prepareValues($data);

        // Create the field and return it
        return $this->model->create($values );

    }

    public function update($id, $data)
    {

        // Fine the field
        $field = $this->model->find($id);

        // Prepare the values
        $values = $this->prepareValues($data);

        // Update the field
        $field->update($values);

        return $field;

    }

    public function deleteById($id)
    {

        $field = $this->model->find($id);
        $field->delete();

    }

    private function prepareValues($data)
    {

        $values = [];
        foreach ($this->fields as $field_code) {
            $values[ $field_code ] = $data[ $field_code ] ?? null;
        }

        return $values;

    }

}