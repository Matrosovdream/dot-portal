<?php

namespace App\Mixins\File;

use App\Models\File;
use App\Repositories\File\FileRepo;

class FileStorage {

    private $base_path = 'uploads/';
    private $base_disk = 'local';

    private $fileRepo;

    public $disks = [
        'local' => 'local',
        'public' => 'public',
    ];

    public function __construct()
    {
        $this->fileRepo = new FileRepo();
    }

    public function uploadFile( $request_file, $path, $disk='local', $data = [] ) {

        // Important variables
        $filepath = $this->base_path . $path;
        $disk = $disk ?? $this->base_disk;

        // Retrieve the file from the request
        $file = request()->file($request_file);

        // Save the file if exists
        if( $file ) {

            $filename = $data['filename'] ?? $file->getClientOriginalName();

            // Save to the disk
            $filePath = $file->storeAs(
                $filepath,
                $filename, 
                ['disk' => $disk]
            );

            // Save to the database by Repo
            if( $filePath ) {

                $fileServer = $this->saveRepo( 
                    $file,
                    [
                        'filepath' => $filePath,
                        'filename' => $filename,
                        'disk' => $disk,
                        'visibility' => '',
                        'user_id' => $data['user_id'] ?? auth('')->user()->id,
                        'tags' => isset($data['tags']) ? $data['tags'] : []
                    ]
                );

                return [
                    'file' => $fileServer,
                    'error' => null
                ];

            }

        } else {
            return [
                'file' => null,
                'error' => 'File not found'
            ];
        }

    }

    protected function saveRepo( $file, $data=[] ) {

        // Save to the database by Repo
        $fields = [
            'filename' => trim( pathinfo($data['filename'], PATHINFO_FILENAME) ),
            'path' => $data['filepath'],
            'type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'description' => '',
            'disk' => $data['disk'],
            'visibility' => $data['visibility'],
            'user_id' => $data['user_id']
        ];
        $fileNew = $this->fileRepo->create( $fields );

        // Add tags
        if( !empty($data['tags']) ) {

            $tags = array_map(fn($tag) => ['name' => $tag], $data['tags']);
            $this->fileRepo->addTags( $fileNew['id'], $tags );

        }

        return $fileNew;

    }

}