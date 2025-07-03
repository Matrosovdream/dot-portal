<?php
namespace App\Repositories\Request;

use App\Repositories\AbstractRepo;
use App\Models\RequestPredefinedValue;
use App\Mixins\File\FileStorage;
use App\References\ServiceReferences;
use App\Models\Request;


class RequestPredefinedValueRepo extends AbstractRepo
{

    protected $model;
    protected $serviceReferences;

    protected $fileStorage;
    protected $fields = [];

    public function __construct()
    {
        $this->model = new RequestPredefinedValue();

        $this->fileStorage = new FileStorage();
        $this->serviceReferences = new ServiceReferences();
    }

    public function syncValue($request_id, $field_code, $value)
    {
        $data = $this->findValue($request_id, $field_code);
        $request = (new RequestRepo)->getById($request_id);
        $form_id = $request['service']['form_id'] ?? null;

        // Field data
        $fields = $this->serviceReferences->getFormFields($form_id);
        $fieldData = $fields[$field_code] ?? null;

        // Upload file handling
        if( 
            isset($fieldData['type']) &&
            $fieldData['type'] == 'file' 
            ) { 
            $file = $this->uploadFile(
                $request_id,
                'fields.'.$field_code,
                ['request #'.$request_id, $fieldData['title']]
            );
            $value = $file['id'] ?? null;
        }

        // If it's an array, convert to string
        if (is_array($value)) {
            $value = implode(',', $value);    
        }

        if (empty($data)) {
            $data = $this->model->newInstance();
            $data->request_id = $request_id;
            $data->field_code = $field_code;
            $data->value = $value;

            $data->save();
        } else {
            $data['Model']->value = $value;
            $data['Model']->save();
        }
        
    }

    public function findValue($request_id, $field_code)
    {
        $data = $this->model->where('request_id', $request_id)->where('field_code', $field_code)->first();
        return $this->mapItem($data);
    }

    public function mapItems($items, $request_id = null)
    {
        if( empty($items) ) {
            return null;
        }

        $items = parent::mapItems($items);
        return $this->mapRequestValues($items, $request_id);

    }

    public function mapRequestValues(array $items, int $request_id) {

        // Map so key => value
        foreach ($items['items'] as $key=>$item) {
            $items['Mapped'][ $item['field_code'] ] = $item['value'];
        }
        
        return $items;

    }

    public function uploadFile( $request_id, $filename, $tags = [] ) {

        $file = $this->fileStorage->uploadFile(
            $filename, 
            'fieldValue/' . $request_id,
            'local',
            ['tags' => $tags]
        );

        if( isset($file['file']['id']) ) {
            return $file['file'];
        }

        return null;

    }

    public function beforeCreate($data)
    {
        // Loop and if it's an array, convert to string
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode(',', $value); 
            }
        }
    }

    public function mapItem($item)  
    {
        if (empty($item)) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'request_id' => $item->request_id,
            'field_code' => $item->field_code,
            'value' => $item->value,
            'Model' => $item
        ];
        return $res;
    }

}