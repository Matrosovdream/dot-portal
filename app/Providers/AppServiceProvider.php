<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Dashboard\Forms\FileUploader;
use App\View\Components\Dashboard\Layout\SidebarMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Register the package components
        Blade::component('file-uploader', FileUploader::class);
        Blade::component('sidebar-menu', SidebarMenu::class);

    }
}
