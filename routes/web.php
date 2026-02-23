<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\VotingController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class,'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class,'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class,'destroy'])
        ->name('profile.destroy');

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

        Route::resource('members', MemberController::class);

        Route::get('members/{member}/profile',
            [MemberController::class,'profile'])
            ->name('members.profile');

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
            ->name('index');

        Route::get('/election/{id}', [VotingController::class,'showElection'])
            ->name('election');

        Route::post('/vote', [VotingController::class,'storeVote'])
            ->name('store');

        Route::get('/results/{id}', [VotingController::class,'results'])
            ->name('results');
            Route::get('/analytics/{id}',
[VotingController::class,'analytics'])
->name('voting.analytics');

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
            ->name('index');

        Route::get('/create', [ElectionController::class,'create'])
            ->name('create');

        Route::post('/', [ElectionController::class,'store'])
            ->name('store');

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
            ->name('index');

        Route::get('/create', [CandidateController::class,'create'])
            ->name('create');

        Route::post('/', [CandidateController::class,'store'])
            ->name('store');

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
            ->names('positions');

    });

    Route::resource('admin/events', EventController::class);
    Route::prefix('admin')->group(function () {
    Route::resource('event-categories', \App\Http\Controllers\EventCategoryController::class);
});

require __DIR__.'/auth.php';