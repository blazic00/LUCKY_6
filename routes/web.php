<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminMainController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\UserMainController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('login');
});

Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserLoginController::class, 'login']);
Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');

// Protect these routes with auth middleware
Route::middleware('auth:user')->group(function () {
    Route::get('/index', [UserMainController::class,'showMainPage'])->name('index');
    Route::get('/lucky_6',[UserMainController::class,'showGamePage'])->name('lucky_6');
    Route::get('/tickets',[TicketController::class,'showUserHistory'])->name('tickets');
    Route::post('/tickets', [TicketController::class, 'store']);
});


Route::get('/admin', function () {
    return view('admin/login');
});

Route::get('/admin/login',[AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login',[AdminLoginController::class, 'login']);
Route::post('/admin/logout',[AdminLoginController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function(){
    Route::get('/index',[AdminMainController::class, 'showMainPage'])->name('index');
    Route::resource('users', AdminUserController::class)->only(['index', 'create','store','destroy']);
    Route::resource('tickets', TicketController::class)->only(['index']);

});

/*

Route::post('/game-rounds/start',[GameRoundController::class, 'start']);*/

