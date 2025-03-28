<?php
namespace App\Repositories\File;

use App\Repositories\AbstractRepo;
use App\Models\File;
use App\Repositories\User\UserRepo;
use App\Repositories\File\FileTagRepo;

class FileRepo extends AbstractRepo
{
    protected $model;

    protected $userRepo;
    protected $fileTagRepo;

    protected $fields = ['user', 'tags'];

    public function __construct()
    {
        $this->model = new File();

        $this->userRepo = new UserRepo();
        $this->fileTagRepo = new FileTagRepo();

    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        // Tags
        $tags = $this->fileTagRepo->mapItems($item->tags);
        $tagGrouped = $this->fileTagRepo->groupTags($tags['items']);

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
            'tags' => $tags,
            'tagGrouped' => $tagGrouped,
            'Model' => $item
        ];
    }

}