<?php

use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/test', [TestController::class, 'test']);
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
Route::put('/user/edit/{user_id}', [UserController::class, 'edit'])->name('user.edit');
Route::put('/user/update', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/destroy', [UserController::class, 'destroy'])->name('user.destroy');
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

    Route::get('/menu_category/index', [MenuCategoryController::class, 'index'])->name('menu_categories.index');
    Route::get('/menu_category/create', [MenuCategoryController::class, 'create'])->name('menu_categories.create');
    Route::post('/menu_category/store', [MenuCategoryController::class, 'store'])->name('menu_categories.store');
    Route::put('/menu_category/edit/{item_id}', [MenuCategoryController::class, 'edit'])->name('menu_categories.edit');
    Route::put('/menu_category/update', [MenuCategoryController::class, 'update'])->name('menu_categories.update');
    Route::delete('/menu_category/destroy', [MenuCategoryController::class, 'destroy'])->name('menu_categories.destroy');

    Route::get('/position/index', [PositionController::class, 'index'])->name('positions.index');
    Route::get('/position/create', [PositionController::class, 'create'])->name('positions.create');
    Route::post('/position/store', [PositionController::class, 'store'])->name('positions.store');
    Route::put('/position/edit/{pos_id}', [PositionController::class, 'edit'])->name('positions.edit');
    Route::put('/position/update', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('/position/destroy', [PositionController::class, 'destroy'])->name('positions.destroy');

    Route::get('/table/index', [TableController::class, 'index'])->name('tables.index');
    Route::get('/table/create', [TableController::class, 'create'])->name('tables.create');
// });
