<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    
    public function download(Request $request, File $file)
    {

        if( !auth()->check() ) {
            abort(404);
        }
        
        // Check if user_id if the auth user or admin user
        if( 
            $file->user_id == auth()->user()->id || 
            auth()->user()->isAdmin() ||
            auth()->user()->isManager()
            ) {
            return Storage::download( $file->path );
        }

    }

    public function show($file_id)
    {

        $file = File::find($file_id);

        if( !$file ) {
            abort(404);
        }

        $path = $file->path;
        $storeDisk = $file->disk;
        
        $disk = Storage::disk( $storeDisk );

        if (!$disk->exists($path)) {
            abort(404);
        }

        $mime = $disk->mimeType($path);
        $file = $disk->get($path);

        return Response::make($file, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);

    }

}
