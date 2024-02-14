<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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


// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');




Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/post', function () {
    return Inertia::render('Post');
})->name('post');

Route::get('/principal', function () {
    return Inertia::render('Principal');
})->name('principal');

Route::get('/login', function () {
    return Inertia::render('Login');
});

Route::get('/tailwind', function () {
    return view('tailwind');
});

Route::get('/layout', function () {
    return view('layout');
});

// Route::post('/login', function (Request $request) {
//     $request->validate([
//         'email' => ['required','email'],
//         'password' => ['required'],
//     ]);
// });
