<?php
namespace App\Repositories\References;

use App\Repositories\AbstractRepo;
use App\Models\RefRequestStatus;


class RefRequestStatusRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    public function __construct()
    {
        $this->model = new RefRequestStatus();
    }

    public function mapItem($item)
    {
        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'color' => $item->color,
            'published' => $item->published,
            'Model' => $item
        ];
        return $res;
    }

}