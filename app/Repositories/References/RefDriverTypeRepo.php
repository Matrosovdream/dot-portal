<?php
namespace App\Repositories\References;

use App\Repositories\AbstractRepo;
use App\Models\RefDriverType;


class RefDriverTypeRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    public function __construct()
    {
        $this->model = new RefDriverType();
    }

    public function mapItem($item)
    {
        $res = [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'order' => $item->order,
            'Model' => $item
        ];
        return $res;
    }

}