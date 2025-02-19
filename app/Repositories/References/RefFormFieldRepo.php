<?php
namespace App\Repositories\References;

use App\Repositories\AbstractRepo;
use App\Models\ReferenceFormField;


class RefFormFieldRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new ReferenceFormField();
    }

    public function getAllByEntity($type, $paginate = 10)
    {
        $items = $this->model->where('entity', $type)->paginate($paginate);
        return $this->mapItems($items);
    }

    public function mapItem($item)
    {
        $res = [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'entity' => $item->entity,
            'type' => $item->type,
            'section' => $item->section,
            'placeholder' => $item->placeholder,
            'tooltip' => $item->tooltip,
            'description' => $item->description,
            'default_value' => $item->default_value,
            'reference_code' => $item->reference_code,
            'icon' => $item->icon,
            'default' => $item->default,
            'classes' => $item->classes,
            'Model' => $item
        ];
        return $res;
    }

}