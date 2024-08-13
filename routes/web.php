<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\EmailTestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
*/

Route::get('/', function () {
    return view('landing');
});

// Socialite login
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);

// Home route
//Route::get('/home', 'HomeController@index')->name('home');

// Test email routes
Route::get('/test-email/StatutDuTicketModifie', [EmailTestController::class, 'sendStatutDuTicketModifie']);
Route::get('/test-email/CreationTicket', [EmailTestController::class, 'sendUserCreated']);
Route::get('/test-email/TicketAssigned', [EmailTestController::class, 'sendTicketAssigned']);
