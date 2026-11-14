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
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\Website;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\EventController as WebsiteEventController;
use App\Http\Controllers\Website\HomeController;
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
| Admin Member Management
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('members', MemberController::class)
            ->middleware('permission:view members');   // Added

        Route::get('members/{member}/profile', [MemberController::class,'profile'])
            ->name('members.profile')
            ->middleware('permission:view members');   // Added

        // Roles & Permissions (newly added)
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)
            ->middleware('permission:view roles');
    });

/*
|--------------------------------------------------------------------------
| Voting System Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin/voting')
    ->as('voting.')
    ->group(function () {

        Route::get('/', [VotingController::class,'index'])
            ->name('index')
            ->middleware('permission:vote');   // Added

        Route::get('/election/{id}', [VotingController::class,'showElection'])
            ->name('election')
            ->middleware('permission:vote');   // Added

        Route::post('/vote', [VotingController::class,'storeVote'])
            ->name('store')
            ->middleware('permission:vote');   // Added

        Route::get('/results/{id}', [VotingController::class,'results'])
            ->name('results')
            ->middleware('permission:view voting analytics');   // Added

        Route::get('/analytics/{id}', [VotingController::class,'analytics'])
            ->name('voting.analytics')
            ->middleware('permission:view voting analytics');   // Added
    });

/*
|--------------------------------------------------------------------------
| Election Management
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin/elections')
    ->name('elections.')
    ->group(function () {

        Route::get('/', [ElectionController::class,'index'])
            ->name('index')
            ->middleware('permission:view elections');   // Added

        Route::get('/create', [ElectionController::class,'create'])
            ->name('create')
            ->middleware('permission:create elections');   // Added

        Route::post('/', [ElectionController::class,'store'])
            ->name('store')
            ->middleware('permission:create elections');   // Added
    });

/*
|--------------------------------------------------------------------------
| Candidate Management
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin/candidates')
    ->name('candidates.')
    ->group(function () {

        Route::get('/', [CandidateController::class,'index'])
            ->name('index')
            ->middleware('permission:view candidates');   // Added

        Route::get('/create', [CandidateController::class,'create'])
            ->name('create')
            ->middleware('permission:create candidates');   // Added

        Route::post('/', [CandidateController::class,'store'])
            ->name('store')
            ->middleware('permission:create candidates');   // Added
    });

/*
|--------------------------------------------------------------------------
| Position Management
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin/positions')
    ->group(function () {
        Route::resource('/', PositionController::class)
            ->names('positions')
            ->middleware('permission:view positions');   // Added
    });

// Events
Route::resource('admin/events', EventController::class)
    ->middleware('permission:view events');   // Added

// Event Categories
Route::prefix('admin')->group(function () {
    Route::resource('event-categories', \App\Http\Controllers\EventCategoryController::class)
        ->middleware('permission:view event categories');   // Added
});

// Documents
Route::resource('documents', DocumentController::class)
    ->middleware('permission:view documents');   // Added

Route::get('documents/{document}/secure-download', [DocumentController::class,'secureDownload'])
    ->name('documents.secure-download')
    ->middleware('permission:view documents');   // Added

// Document Categories
Route::resource('document-categories', DocumentCategoryController::class)
    ->middleware('permission:view document categories');   // Added

// Member creation wizard (under admin prefix)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/members/create', [MemberController::class,'create'])
        ->name('members.create')
        ->middleware('permission:create members');   // Added

    Route::post('/members/wizard/store', [MemberController::class,'wizardStore'])
        ->name('members.wizard.store')
        ->middleware('permission:create members');   // Added
});

// Duplicate? You have a second resource('members') outside admin prefix – be careful
// I'll assume it's intentional; add permission middleware there too
Route::resource('members', MemberController::class)
    ->middleware('permission:view members');   // Added

// Academic background (for members)
Route::prefix('academic')->middleware(['auth'])->group(function(){
    Route::get('/', [MemberAcademicBackgroundController::class,'index'])
        ->name('academic.index')
        ->middleware('permission:view members');   // Added

    Route::get('/create', [MemberAcademicBackgroundController::class,'create'])
        ->name('academic.create')
        ->middleware('permission:create members');   // Added

    Route::post('/', [MemberAcademicBackgroundController::class,'store'])
        ->name('academic.store')
        ->middleware('permission:create members');   // Added
});

// Membership Types
Route::resource('membership-types', \App\Http\Controllers\Admin\MembershipTypeController::class)
    ->middleware('permission:view membership types');   // Added

// Academic Institutions
Route::resource('academic-institutions', \App\Http\Controllers\Admin\AcademicInstitutionController::class)
    ->middleware('permission:view academic institutions');   // Added

// Members export
Route::get('/admin/members/export/{type}', [App\Http\Controllers\Admin\MemberController::class,'export'])
    ->name('admin.members.export')
    ->middleware('permission:view members');   // Added

// Password change
Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [PasswordController::class, 'showChangeForm'])
        ->name('password.change')
        ->middleware('permission:change password');   // Added

    Route::post('/password/change', [PasswordController::class, 'update'])
        ->name('password.update')
        ->middleware('permission:change password');   // Added
});

// Location AJAX endpoints (no permission needed, or you can add if desired)
Route::get('/regions', [App\Http\Controllers\LocationController::class, 'getRegions'])->name('regions.list');
Route::get('/districts/{region}', [App\Http\Controllers\LocationController::class, 'getDistricts'])->name('districts.by.region');

require __DIR__.'/auth.php';