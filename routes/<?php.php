<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\VotingController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\PositionController;

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
| Authenticated Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile Management
    |--------------------------------------------------------------------------
    */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile','edit')->name('profile.edit');
        Route::patch('/profile','update')->name('profile.update');
        Route::delete('/profile','destroy')->name('profile.destroy');
    });

});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Members
        |--------------------------------------------------------------------------
        */
        Route::resource('members', MemberController::class);
        Route::get('members/{member}/profile',
            [MemberController::class,'profile']
        )->name('members.profile');

        /*
        |--------------------------------------------------------------------------
        | Elections
        |--------------------------------------------------------------------------
        */
        Route::resource('elections', ElectionController::class);

        /*
        |--------------------------------------------------------------------------
        | Candidates
        |--------------------------------------------------------------------------
        */
        Route::resource('candidates', CandidateController::class);

        /*
        |--------------------------------------------------------------------------
        | Candidate Approval
        |--------------------------------------------------------------------------
        */
        Route::post('candidates/{id}/approve',
            [CandidateController::class,'approve']
        )->name('candidates.approve');

        /*
        |--------------------------------------------------------------------------
        | Positions
        |--------------------------------------------------------------------------
        */
        Route::resource('positions', PositionController::class);

    });

/*
|--------------------------------------------------------------------------
| Voting System Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
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
    });

require __DIR__.'/auth.php';