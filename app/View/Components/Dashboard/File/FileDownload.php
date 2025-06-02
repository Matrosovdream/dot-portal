<?php

namespace App\View\Components\Dashboard\File;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Repositories\File\FileRepo;

class FileDownload extends Component
{

    protected $fileRepo;

    public function __construct(
        protected ?int $fileId,
        protected ?array $file
    )
    {
        $this->fileRepo = app(FileRepo::class);

        // Let's find the file by ID
        $file = $this->fileRepo->getById($this->fileId);
        if( $file ) {
            $this->file = $file;
        }
        
    }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.file.file-download');
    }
}
