<?php

namespace App\Http\Controllers\Api\V1\Files;

use App\Actions\Api\V1\Files\FileActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Files\StoreFileRequest;
use App\Http\Resources\V1\Files\FileResource;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct(protected FileActions $actions) {}

    public function store(StoreFileRequest $request): JsonResponse
    {
        $rec = $this->actions->create($request->user(), $request->file('file'));
        return (new FileResource($rec))->response()->setStatusCode(201);
    }

    public function show(Request $request, File $file)
    {
        return new FileResource($this->actions->show($file, $request->user()));
    }

    public function download(Request $request, File $file)
    {
        $f = $this->actions->show($file, $request->user());

        return Storage::disk($f->disk ?: config('filesystems.default'))
            ->download($f->path, $f->filename);
    }
}
