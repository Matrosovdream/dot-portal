<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverDocument;


class DriverDocumentRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new DriverDocument();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'type' => $item->type,
            'title' => $item->title,
            'file_id' => $item->file_id,
            'extension' => $item->extension,
            'Model' => $item
        ];

        return $res;
    }

}