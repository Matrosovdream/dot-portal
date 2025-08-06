<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Dashboard\Forms\FileUploader;
use App\View\Components\Dashboard\File\FileDownload;
use App\View\Components\Dashboard\Forms\Select;
use App\View\Components\Dashboard\Forms\Date;
use App\View\Components\Dashboard\Layout\SidebarMenu;
use App\View\Components\Dashboard\Layout\TopbarNotifications;
use App\View\Components\Dashboard\Layout\TopbarSearch;
use App\Services\Mail\MailgunService;
use App\Contracts\Saferweb\SaferwebInterface;
use App\Contracts\Mail\MailServiceInterface;
use App\Services\Saferweb\GovernApiService;
use App\Contracts\Payment\PaymentInterface;
use App\Services\Payments\AuthnetService;


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
        Blade::component('sidebar-menu', SidebarMenu::class);

        // Form components
        Blade::component('file-uploader', FileUploader::class);
        Blade::component('file-download', FileDownload::class);
        Blade::component('select', Select::class);
        Blade::component('date', Date::class);
        Blade::component('topbar-notifications', TopbarNotifications::class);
        Blade::component('topbar-search', TopbarSearch::class);

        // Services binding
        $this->app->bind(MailServiceInterface::class, MailgunService::class);
        $this->app->bind(SaferwebInterface::class, GovernApiService::class);
        $this->app->bind(PaymentInterface::class, AuthnetService::class);

    }

}
