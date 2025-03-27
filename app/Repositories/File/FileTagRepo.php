<?php
namespace App\Repositories\File;

use App\Repositories\AbstractRepo;
use App\Models\FileTag;

class FileTagRepo extends AbstractRepo
{
    protected $model;
    protected $fields = [];

    public function __construct()
    {
        $this->model = new FileTag();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'name' => $item->name,
            'file_id' => $item->file_id,
            'Model' => $item
        ];
    }

}