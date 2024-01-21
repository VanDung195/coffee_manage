<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

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
    return view('layout.master');
});
Route::get('/test', [TestController::class, 'test']);
Route::get('/test2', [TestController::class, 'test2'])->name('test2');

Route::get('/index',[TableController::class, 'index']);
Route::get('/search', [MenuItemController::class, 'search'])->name('item.search');

Route::post('/create',[InvoiceController::class, 'create'])->name('invoice.creatae');
Route::get('/create', [TestController::class, 'create']);