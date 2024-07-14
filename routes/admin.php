<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/test', [TestController::class, 'test']);
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
Route::put('/edit/{user_id}', [UserController::class, 'edit'])->name('user.edit');
Route::put('/update', [UserController::class, 'update'])->name('user.update');
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
//Thống kê
// Route::middleware([AdminMiddleware::class])->group(function(){
    Route::get('/statistic_day', [StatisticController::class, 'statistic_day_i'])->name('statistic.day_i');
    Route::get('/day', [StatisticController::class, 'statistic_day'])->name('statistic.day');
    Route::get('/statistic_month', [StatisticController::class, 'statistic_month_i'])->name('statistic.month_i');
    Route::get('/month', [StatisticController::class, 'statistic_month'])->name('statistic.month');
    Route::get('/statistic_year', [StatisticController::class, 'statistic_year_i'])->name('statistic.year_i');
    Route::get('/year', [StatisticController::class, 'statistic_year'])->name('statistic.year');
    Route::get('/statistic_date_range', [StatisticController::class, 'statistic_date_range_i'])->name('statistic.date_range_i');
    Route::get('/date_range', [StatisticController::class, 'statistic_date_range'])->name('statistic.range');

    Route::get('/menu_items', [MenuItemController::class, 'index'])->name('menu_items.index');
    Route::get('/create', [MenuItemController::class, 'create'])->name('menu_items.create');
    Route::post('/store', [MenuItemController::class, 'store'])->name('menu_items.store');
    Route::put('/edit/{menu_item}', [MenuItemController::class, 'edit'])->name('menu_items.edit');
    Route::put('/update', [MenuItemController::class, 'update'])->name('menu_items.update');
    Route::delete('/destroy', [MenuItemController::class, 'destroy'])->name('menu_items.destroy');
// });
