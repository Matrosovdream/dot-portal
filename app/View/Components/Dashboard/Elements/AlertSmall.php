<?php

namespace App\View\Components\Dashboard\Elements;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AlertSmall extends Component
{
    public function __construct(
        public string $classes = '',
    )
    { }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.elements.alert-small');
    }
}
