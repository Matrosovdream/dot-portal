<?php

namespace App\View\Components\Dashboard\File;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FileDownload extends Component
{

    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.file.file-download');
    }
}
