<?php
namespace App\Repositories\Request;

use App\Repositories\AbstractRepo;
use App\Repositories\References\RefFormFieldRepo;
use App\Models\RequestFieldValue;
use App\Mixins\File\FileStorage;



class RequestFieldValueRepo extends AbstractRepo
{

    private $refFormFieldRepo;

    protected $fileStorage;

    protected $model;

    protected $fields = ['field'];

    public function __construct()
    {
        $this->model = new RequestFieldValue();

        // References
        $this->refFormFieldRepo = new RefFormFieldRepo();

        $this->fileStorage = new FileStorage();
    }

    public function syncValue($request_id, $field_id, $value)
    {
        $data = $this->model->where('request_id', $request_id)->where('field_id', $field_id)->first();

        // Field data
        $field = $this->refFormFieldRepo->getById($field_id);

        // Upload file handling
        if( $field['type'] == 'file' ) {
            $file = $this->uploadFile(
                $request_id,
                'fields.'.$field['id'],
                ['request #'.$request_id, $field['title']]
            );
            $value = $file['id'] ?? null;
        }

        if (empty($data)) {
            $data = new RequestFieldValue();
            $data->request_id = $request_id;
            $data->field_id = $field_id;
        }
        $data->value = $value;
        $data->save();
    }

    public function findValue($request_id, $field_id)
    {
        $data = $this->model->where('request_id', $request_id)->where('field_id', $field_id)->first();
        return $this->mapItem($data);
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

    public function mapItem($item)  
    {
        if (empty($item)) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'request_id' => $item->request_id,
            'field' => $this->refFormFieldRepo->mapItem($item->field),
            'value' => $item->value,
            'Model' => $item
        ];
        return $res;
    }

}