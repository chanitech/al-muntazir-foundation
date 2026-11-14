<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SiteSettings::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Public website settings ($site) for the website and its admin screens.
        View::composer(['website.*', 'admin.website.*', 'auth.*'], function ($view) {
            $view->with('site', $this->app->make(SiteSettings::class));
        });

        // "Messages" item in the admin sidebar, with the number of unread messages.
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $unread = 0;

            try {
                if (Schema::hasTable('contact_messages')) {
                    $unread = ContactMessage::unread()->count();
                }
            } catch (Throwable) {
                // The menu must render even if the table is not migrated yet.
            }

            $event->menu->addAfter('website', [
                'key' => 'website_messages',
                'text' => 'Website Messages',
                'route' => 'admin.messages.index',
                'icon' => 'fas fa-envelope',
                'can' => 'manage website',
                'label' => $unread > 0 ? $unread : null,
                'label_color' => 'danger',
                'active' => ['admin/messages*'],
            ]);
        });
    }
}
