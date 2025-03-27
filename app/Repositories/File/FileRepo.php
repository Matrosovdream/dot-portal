<?php
namespace App\Repositories\File;

use App\Repositories\AbstractRepo;
use App\Models\File;
use App\Repositories\User\UserRepo;

class FileRepo extends AbstractRepo
{
    protected $model;

    protected $userRepo;

    protected $fields = ['user', 'tags'];

    public function __construct()
    {
        $this->model = new File();

        $this->userRepo = new UserRepo();

    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'filename' => $item->filename,
            'path' => $item->path,
            'type' => $item->type,
            'size' => $item->size,
            'extension' => $item->extension,
            'desription' => $item->description,
            'disk' => $item->disk,
            'visibility' => $item->visibility,
            'user' => $this->userRepo->mapItem($item->user),
            'Model' => $item
        ];
    }

}