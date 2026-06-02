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
use App\View\Components\Dashboard\Elements\AlertSmall;
use App\Services\Mail\MailgunService;
use App\Contracts\Saferweb\SaferwebInterface;
use App\Contracts\Mail\MailServiceInterface;
use App\Services\Saferweb\GovernApiService;
use App\Contracts\Payment\PaymentInterface;
use App\Services\Payments\AuthnetService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;


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
        Blade::component('alert-small', AlertSmall::class);

        // Services binding
        $this->app->bind(MailServiceInterface::class, MailgunService::class);
        $this->app->bind(SaferwebInterface::class, GovernApiService::class);
        $this->app->bind(PaymentInterface::class, AuthnetService::class);

        // SPA cutover: framework auth emails must link to the Vue SPA at '/',
        // not the (now-unrouted) Breeze web routes. Without these overrides the
        // default ResetPassword / VerifyEmail notifications build URLs via the
        // bare route names 'password.reset' / 'verification.verify', which no
        // longer exist and would throw RouteNotFoundException.
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return config('app.url') . '/reset-password/' . $token
                . '?email=' . urlencode($notifiable->getEmailForPasswordReset());
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            $signedApiUrl = URL::temporarySignedRoute(
                'api.v1.auth.verification.verify',
                now()->addMinutes(60),
                [
                    'id'   => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            // Hand the signed API URL to the SPA, which completes verification
            // by GETting it (with the session cookie) from VerifyEmailView.
            return config('app.url') . '/verify-email?verify=' . urlencode($signedApiUrl);
        });

    }

}
