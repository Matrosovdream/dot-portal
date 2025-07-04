<?php

namespace App\View\Components\Dashboard\Forms;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FileUploader extends Component
{

    public function __construct(
        public string $inputName,
        public ?string $inputId=null,
        public string|array|null $value = null,
        public ?string $accept,
        public ?bool $multiple,
        public ?bool $required,
        public ?string $title,
        public ?string $label,
        public ?string $note,
        public ?string $description,
    )
    {

        if( !$inputId || empty( $inputId ) ) {
            $this->inputId = $inputName;
        }

    }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.forms.file-uploader');
    }
}
