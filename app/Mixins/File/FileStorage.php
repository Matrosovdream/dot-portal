<?php

namespace App\Mixins\File;

class FileStorage {

    public function uploadFile( $request_file, $data = [] ) {

        $path = 'uploads/travellers/';
        $disk = 'local';

        $file = request()->file($request_file);
        $filename = $file->getClientOriginalName();
        $filesize = $file->getSize();
        $type = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();

        // We set an origin filename to the file
        $filePath = request()->file($request_file)->storeAs($path, $filename, $disk);

    }

    protected function insertDB( $data ) {
        
        // Insert into the database
        $file = new File();
        $file->filename = $filename;
        $file->path = $filePath;
        $file->type = $type;
        $file->size = $filesize;
        $file->extension = $extension;
        $file->description = '';
        $file->disk = $disk;
        $file->visibility = 'private';
        $file->user_id = auth('')->user()->id;
        $file->save();

    }



}