<?php

namespace App\View\Components\Dashboard\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{

    public function __construct(
        public string $inputName,
        public ?string $inputId,
        public ?string $value,
        public ?array $options,
        public ?bool $multiple,
        public ?bool $required,
        public ?string $label,
        public ?string $note,
        public ?string $description,
    )
    { }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.forms.select');
    }
}
