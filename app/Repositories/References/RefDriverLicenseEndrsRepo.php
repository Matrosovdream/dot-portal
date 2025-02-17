<?php
namespace App\Repositories\References;

use App\Repositories\AbstractRepo;
use App\Models\RefDriverLicenseEndrs;


class RefDriverLicenseEndrsRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    public function __construct()
    {
        $this->model = new RefDriverLicenseEndrs();
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