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

    public function addTags($file_id, $tags)
    {

        $file = $this->model->find($file_id);

        if( !$file ) { return false; }

        foreach( $tags as $tag ) {
            $file->tags()->create( $tag );
        }
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
            'title' => $item->filename,
            'filename' => $item->filename,
            'path' => $item->path,
            'type' => $item->type,
            'size' => $item->size,
            'sizeFormatted' => $this->formatBytes($item->size),
            'extension' => $item->extension,
            'desription' => $item->description,
            'disk' => $item->disk,
            'visibility' => $item->visibility,
            'user' => $this->userRepo->mapItem($item->user),
            'tags' => $tags,
            'tagGrouped' => $tagGrouped,
            'downloadUrl' => route('file.download', $item->id),
            'Model' => $item
        ];

    }

    public function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

}