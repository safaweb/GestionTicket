<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('landing');
});


// socialite login
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);
Route::get('/home', 'HomeController@index')->name('home'); // Remove 'verified'


Route::get('/test-email', function () {
    $owner = App\Models\User::find(1); // Replace with an existing user ID
    $owner->notify(new App\Notifications\StatutDuTicketModifie());
});

Route::get('/test-email', function () {
    $owner = App\Models\User::find(1); // Replace with an existing user ID
    $owner->notify(new App\Notifications\UserCreated());
});





