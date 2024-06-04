<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
});

Route::get('/signup', [UserController::class, 'signup'])->name('signup');
Route::get('/signin', [UserController::class, 'signin'])->name('signin');
Route::post('/register', [UserController::class,'register'])->name('register');
Route::post('/login', [UserController::class,'login'])->name('login');
Route::get('/dashboard', [UserController::class,'dashboard'])->name('dashboard');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [UserController::class,'forgotPassword'])->name('forgot.password');
Route::post('/forgot-password', [UserController::class,'forgotPasswordPost'])->name('forgot.password.post');
Route::post('/reset-password', [UserController::class,'resetPasswordPost'])->name('reset.password.post');
Route::get('/reset-password/{token}', [UserController::class,'resetPassword'])->name('reset.password');

// Route::post('/register', [RegisterController::class, ])