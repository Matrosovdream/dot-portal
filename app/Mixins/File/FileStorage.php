<?php

namespace App\Mixins\File;

use App\Models\File;

class FileStorage {

    private $base_path = 'uploads/';
    private $base_disk = 'local';

    public $disks = [
        'local' => 'local',
        'public' => 'public',
    ];

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
                $this->saveRepo( 
                    $file,
                    [
                        'filepath' => $filePath,
                        'filename' => $filename,
                        'disk' => $disk,
                        'visibility' => '',
                        'user_id' => $data['user_id'] ?? auth('')->user()->id
                    ]
                );
            }

        } else {
            return [
                'file_id' => null,
                'error' => 'File not found'
            ];
        }

    }

    protected function saveRepo( $file, $data=[] ) {

        $filesize = $file->getSize();
        $type = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();
        
        // Insert into the database
        $file = new File();
        $file->filename = $data['filename'];
        $file->path = $data['filepath'];
        $file->type = $type;
        $file->size = $filesize;
        $file->extension = $extension;
        $file->description = '';
        $file->disk = $data['disk'];
        $file->visibility = $data['visibility'];
        $file->user_id = $data['user_id'];
        $file->save();

        return $file->id;

    }



}