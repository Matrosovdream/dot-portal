<?php
namespace App\Repositories\Request;

use App\Repositories\AbstractRepo;
use App\Models\RequestPredefinedValue;


class RequestPredefinedValueRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new RequestPredefinedValue();
    }

    public function syncValue($request_id, $field_id, $value)
    {
        $data = $this->model->where('request_id', $request_id)->where('field_id', $field_id)->first();

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