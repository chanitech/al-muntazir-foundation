<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\MembershipApplication;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
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
        // Super Admins can do everything, even if a permission is missing
        // from their role, so they can never lock themselves out.
        Gate::before(fn ($user) => method_exists($user, 'hasRole') && $user->hasRole('Super Admin') ? true : null);

        // Public website settings ($site) for the website and its admin screens.
        View::composer(['website.*', 'admin.website.*', 'auth.*'], function ($view) {
            $view->with('site', $this->app->make(SiteSettings::class));
        });

        // Sidebar items that show a live counter: website messages and
        // membership applications waiting for review.
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $unread = $this->countSafely('contact_messages', fn () => ContactMessage::unread()->count());
            $pendingApplications = $this->countSafely('membership_applications', fn () => MembershipApplication::pending()->count());

            $event->menu->addAfter('members', [
                'key' => 'membership_applications',
                'text' => 'Membership Applications',
                'route' => 'admin.applications.index',
                'icon' => 'fas fa-user-plus',
                'can' => 'view members',
                'label' => $pendingApplications > 0 ? $pendingApplications : null,
                'label_color' => 'warning',
                'active' => ['admin/applications*'],
            ]);

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

    /** Runs a count for the menu without breaking it if the table is missing. */
    private function countSafely(string $table, callable $count): int
    {
        try {
            return Schema::hasTable($table) ? (int) $count() : 0;
        } catch (Throwable) {
            return 0;
        }
    }
}
