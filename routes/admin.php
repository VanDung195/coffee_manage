<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StatisticController;
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
// Route::group([
//     'as' => 'statistic',
//     'prefix' => 'statistic',
// ], 
//     function() {
//         Route::get('/statistic', [StatisticController::class, 'statistic_day'])->name('day');
//     }
// );
Route::get('/asd', [StatisticController::class, 'statistic_day'])->name('statistic.day');