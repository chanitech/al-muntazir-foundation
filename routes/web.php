<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\VotingController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\Admin\MemberAcademicBackgroundController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MembershipTypeController;
use App\Http\Controllers\Admin\AcademicInstitutionController;
use App\Http\Controllers\Admin\MembershipApplicationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\Website;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\EventController as WebsiteEventController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\MembershipApplicationController as MembershipApplicationFormController;
use App\Http\Controllers\Website\NewsController;
use App\Http\Controllers\Website\NewsletterController;
use App\Http\Controllers\Website\PageController;
use App\Http\Controllers\Website\ProgramController as WebsiteProgramController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::name('site.')->group(function () {
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/programs', [WebsiteProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/{program}', [WebsiteProgramController::class, 'show'])->name('programs.show');
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{post}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/events', [WebsiteEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [WebsiteEventController::class, 'show'])->whereNumber('event')->name('events.show');
    Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
    Route::get('/contact', [ContactController::class, 'show'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
    Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:10,1')->name('newsletter');
    Route::get('/membership/apply', [MembershipApplicationFormController::class, 'create'])->name('membership.apply');
    Route::post('/membership/apply', [MembershipApplicationFormController::class, 'store'])->middleware('throttle:5,1')->name('membership.store');
});

/*
|--------------------------------------------------------------------------
| Admin: Website content (Admin → Website)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'permission:manage website'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('website-settings', [Website\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('website-settings', [Website\SettingController::class, 'update'])->name('settings.update');

        $modules = [
            // uri         => [route name,  controller]
            'sliders' => ['sliders', Website\SliderController::class],
            'programs' => ['programs', Website\ProgramController::class],
            'news' => ['posts', Website\PostController::class],
            'team' => ['team', Website\TeamMemberController::class],
            'testimonials' => ['testimonials', Website\TestimonialController::class],
            'gallery' => ['gallery', Website\GalleryItemController::class],
            'partners' => ['partners', Website\PartnerController::class],
            'impact-stats' => ['stats', Website\ImpactStatController::class],
        ];

        foreach ($modules as $uri => [$name, $controller]) {
            Route::resource($uri, $controller)->except('show')->names($name)->parameters([$uri => 'id']);
            Route::patch("{$uri}/{id}/toggle", [$controller, 'toggle'])->name("{$name}.toggle");
        }

        Route::get('messages', [Website\ContactMessageController::class, 'index'])->name('messages.index');
        Route::post('messages/mark-all-read', [Website\ContactMessageController::class, 'markAllRead'])->name('messages.mark-all-read');
        Route::get('messages/{message}', [Website\ContactMessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}/unread', [Website\ContactMessageController::class, 'markUnread'])->name('messages.unread');
        Route::delete('messages/{message}', [Website\ContactMessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('subscribers', [Website\SubscriberController::class, 'index'])->name('subscribers.index');
        Route::get('subscribers/export', [Website\SubscriberController::class, 'export'])->name('subscribers.export');
        Route::delete('subscribers/{subscriber}', [Website\SubscriberController::class, 'destroy'])->name('subscribers.destroy');

        Route::post('editor-upload', Website\EditorUploadController::class)->name('editor.upload');
    });

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class,'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Membership system (admin)
|--------------------------------------------------------------------------
|
| Every route needs a login plus the permission for the action:
| "view …" to open lists, "create …" to add, "edit …" to change and
| "delete …" to remove. Super Admin has all permissions; other roles are
| managed under Account Settings → Roles & Permissions.
|
*/

Route::middleware('auth')->group(function () {

    // Members
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('members/export/{type}', [MemberController::class, 'export'])
            ->whereIn('type', ['csv', 'excel', 'pdf'])
            ->name('members.export')
            ->middleware('permission:view members');

        Route::post('members/wizard/store', [MemberController::class, 'wizardStore'])
            ->name('members.wizard.store')
            ->middleware('permission:create members');

        Route::get('members/{member}/profile', [MemberController::class, 'profile'])
            ->name('members.profile')
            ->middleware('permission:view members');

        Route::resource('members', MemberController::class)
            ->only(['index', 'create', 'edit', 'update', 'destroy'])
            ->middlewareFor('index', 'permission:view members')
            ->middlewareFor('create', 'permission:create members')
            ->middlewareFor(['edit', 'update'], 'permission:edit members')
            ->middlewareFor('destroy', 'permission:delete members');

        // Online membership applications from the website
        Route::get('applications', [MembershipApplicationController::class, 'index'])
            ->name('applications.index')->middleware('permission:view members');
        Route::get('applications/{application}', [MembershipApplicationController::class, 'show'])
            ->name('applications.show')->middleware('permission:view members');
        Route::post('applications/{application}/approve', [MembershipApplicationController::class, 'approve'])
            ->name('applications.approve')->middleware('permission:create members');
        Route::post('applications/{application}/reject', [MembershipApplicationController::class, 'reject'])
            ->name('applications.reject')->middleware('permission:create members');
        Route::delete('applications/{application}', [MembershipApplicationController::class, 'destroy'])
            ->name('applications.destroy')->middleware('permission:delete members');

        // Roles & permissions
        Route::resource('roles', RoleController::class)
            ->except('show')
            ->middlewareFor('index', 'permission:view roles')
            ->middlewareFor(['create', 'store'], 'permission:create roles')
            ->middlewareFor(['edit', 'update'], 'permission:edit roles')
            ->middlewareFor('destroy', 'permission:delete roles');
    });

    // Voting (members) and results (admins)
    Route::prefix('admin/voting')->name('voting.')->group(function () {
        Route::get('/', [VotingController::class, 'index'])->name('index')->middleware('permission:vote');
        Route::get('/election/{id}', [VotingController::class, 'showElection'])->name('election')->middleware('permission:vote');
        Route::post('/vote', [VotingController::class, 'storeVote'])->name('store')->middleware('permission:vote');
        Route::get('/results/{id}', [VotingController::class, 'results'])->name('results')->middleware('permission:view voting analytics');
        Route::get('/analytics', [VotingController::class, 'analytics'])->name('analytics')->middleware('permission:view voting analytics');
    });

    // Elections, candidates and positions
    Route::resource('admin/elections', ElectionController::class)
        ->only(['index', 'create', 'store', 'edit', 'update'])
        ->names('elections')
        ->parameters(['elections' => 'election'])
        ->middlewareFor('index', 'permission:view elections')
        ->middlewareFor(['create', 'store'], 'permission:create elections')
        ->middlewareFor(['edit', 'update'], 'permission:edit elections');

    Route::resource('admin/candidates', CandidateController::class)
        ->only(['index', 'create', 'store', 'destroy'])
        ->names('candidates')
        ->parameters(['candidates' => 'candidate'])
        ->middlewareFor('index', 'permission:view candidates')
        ->middlewareFor(['create', 'store'], 'permission:create candidates')
        ->middlewareFor('destroy', 'permission:delete candidates');

    Route::resource('admin/positions', PositionController::class)
        ->except('show')
        ->names('positions')
        ->parameters(['positions' => 'position'])
        ->middlewareFor('index', 'permission:view positions')
        ->middlewareFor(['create', 'store'], 'permission:create positions')
        ->middlewareFor(['edit', 'update'], 'permission:edit positions')
        ->middlewareFor('destroy', 'permission:delete positions');

    // Events and event categories
    Route::resource('admin/events', EventController::class)
        ->except('show')
        ->names('events')
        ->parameters(['events' => 'event'])
        ->middlewareFor('index', 'permission:view events')
        ->middlewareFor(['create', 'store'], 'permission:create events')
        ->middlewareFor(['edit', 'update'], 'permission:edit events')
        ->middlewareFor('destroy', 'permission:delete events');

    Route::resource('admin/event-categories', EventCategoryController::class)
        ->except('show')
        ->names('event-categories')
        ->parameters(['event-categories' => 'event_category'])
        ->middlewareFor('index', 'permission:view event categories')
        ->middlewareFor(['create', 'store'], 'permission:create event categories')
        ->middlewareFor(['edit', 'update'], 'permission:edit event categories')
        ->middlewareFor('destroy', 'permission:delete event categories');

    // Document library
    Route::get('documents/{document}/secure-download', [DocumentController::class, 'secureDownload'])
        ->name('documents.secure-download')
        ->middleware('permission:view documents');

    Route::resource('documents', DocumentController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy'])
        ->middlewareFor(['index', 'show'], 'permission:view documents')
        ->middlewareFor(['create', 'store'], 'permission:create documents')
        ->middlewareFor('destroy', 'permission:delete documents');

    Route::resource('document-categories', DocumentCategoryController::class)
        ->except('show')
        ->middlewareFor('index', 'permission:view document categories')
        ->middlewareFor(['create', 'store'], 'permission:create document categories')
        ->middlewareFor(['edit', 'update'], 'permission:edit document categories')
        ->middlewareFor('destroy', 'permission:delete document categories');

    // Member settings
    Route::resource('membership-types', MembershipTypeController::class)
        ->except('show')
        ->middlewareFor('index', 'permission:view membership types')
        ->middlewareFor(['create', 'store'], 'permission:create membership types')
        ->middlewareFor(['edit', 'update'], 'permission:edit membership types')
        ->middlewareFor('destroy', 'permission:delete membership types');

    Route::resource('academic-institutions', AcademicInstitutionController::class)
        ->except('show')
        ->middlewareFor('index', 'permission:view academic institutions')
        ->middlewareFor(['create', 'store'], 'permission:create academic institutions')
        ->middlewareFor(['edit', 'update'], 'permission:edit academic institutions')
        ->middlewareFor('destroy', 'permission:delete academic institutions');

    // Academic background (for members)
    Route::prefix('academic')->name('academic.')->group(function () {
        Route::get('/', [MemberAcademicBackgroundController::class, 'index'])->name('index')->middleware('permission:view members');
        Route::get('/create', [MemberAcademicBackgroundController::class, 'create'])->name('create')->middleware('permission:create members');
        Route::post('/', [MemberAcademicBackgroundController::class, 'store'])->name('store')->middleware('permission:create members');
    });

    // Change own password (Account Settings)
    Route::get('/password/change', [PasswordController::class, 'showChangeForm'])
        ->name('password.change')
        ->middleware('permission:change password');

    Route::post('/password/change', [PasswordController::class, 'update'])
        ->name('password.change.update')
        ->middleware('permission:change password');
});

// Location AJAX endpoints (no permission needed, or you can add if desired)
Route::get('/regions', [App\Http\Controllers\LocationController::class, 'getRegions'])->name('regions.list');
Route::get('/districts/{region}', [App\Http\Controllers\LocationController::class, 'getDistricts'])->name('districts.by.region');

require __DIR__.'/auth.php';