<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

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


}
