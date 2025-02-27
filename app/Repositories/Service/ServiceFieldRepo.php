<?php
namespace App\Repositories\Service;

use App\Repositories\AbstractRepo;
use App\Repositories\References\RefFormFieldRepo;
use App\Models\ServiceField;


class ServiceFieldRepo extends AbstractRepo
{

    private $refFormFieldRepo;
    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new ServiceField();

        // References
        $this->refFormFieldRepo = new RefFormFieldRepo();
    }

    public function mapItem($item)  
    {
        if (empty($item)) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'entity' => $item->entity,
            'section' => $item->section,
            'placeholder' => $item->placeholder,
            'required' => $item->required,
            'default_value' => $item->default_value,
            'classes' => $item->classes,
            'order' => $item->order,
            'field_id' => $item->field_id,
            'field' => $item->refFormFieldRepo->mapItem( $item->field ),
            'service_id' => $item->service_id,
            'Model' => $item
        ];
        return $res;
    }

}