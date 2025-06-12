<?php

namespace App\View\Components\Dashboard\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TopbarSearch extends Component
{

    public function __construct()
    {
        
    }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.layout.topbar-search');
    }
}
