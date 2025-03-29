<?php

namespace App\View\Components\Dashboard\Layout;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Helpers\adminSettingsHelper;

class SidebarMenu extends Component
{

    public $sidebarMenu;

    public function __construct()
    {
        $this->sidebarMenu = adminSettingsHelper::getSidebarMenu();
    }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.layout.sidebar-menu');
    }

}
