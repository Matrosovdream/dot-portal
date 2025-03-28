<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverDocument;
use App\Repositories\File\FileRepo;


class DriverDocumentRepo extends AbstractRepo
{

    protected $model;

    protected $fileRepo;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    protected $withRelations = ['file'];

    public function __construct()
    {
        $this->model = new DriverDocument();

        $this->fileRepo = new FileRepo();
    }

    public function mapItems($items)
    {
        $res = [];
        foreach($items as $item) {
            $res[] = $this->mapItem($item);
        }

        // Group by type field
        $groupType = collect($res)->groupBy('type')->toArray();

        return [
            'items' => $res,
            'groupType' => $groupType,
            'Model' => $items
        ];

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
            'file' => $this->fileRepo->mapItem($item->file),
            'extension' => $item->extension,
            'Model' => $item
        ];

        return $res;
    }

}