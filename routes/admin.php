<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [TestController::class, 'test']);
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
// Route::group([
//     'as' => 'users',
//     'prefix' => 'users'],
//     function() {
        
//     }
// );