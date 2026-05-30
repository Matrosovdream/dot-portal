<?php

namespace App\Actions\Api\V1\Files;

use App\Models\File;
use App\Repositories\File\FileRepo;
use Illuminate\Http\UploadedFile;

class FileActions
{
    public function __construct(
        protected FileRepo $repo,
    ) {}

    public function create($auth, UploadedFile $file): File
    {
        $disk = 'local';
        $path = $file->store('files', $disk);

        return $this->repo->getModel()::query()->create([
            'filename'   => $file->getClientOriginalName(),
            'path'       => $path,
            'type'       => 'file',
            'size'       => (string) $file->getSize(),
            'extension'  => $file->getClientOriginalExtension(),
            'disk'       => $disk,
            'visibility' => 'private',
            'user_id'    => $auth->id,
        ]);
    }

    public function show(File $file, $auth): File
    {
        $this->authorize($file, $auth);
        return $file;
    }

    protected function authorize(File $file, $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) {
            return;
        }

        if ($file->user_id === $auth->id) {
            return;
        }

        abort(404);
    }
}
