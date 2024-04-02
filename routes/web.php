<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceApiController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\AdminMiddleware;
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

// Route::get('/', function () {
//     return view('layout.master');
// });
Route::get('/test', [TestController::class, 'test']);
Route::get('/test2', [TestController::class, 'test2'])->name('test2');
Route::get('/test3', [TestController::class, 'test3']);
Route::get('/testApi', [InvoiceApiController::class, 'index']);
Route::get('/', [InvoiceApiController::class, 'index'])->name('api.invoices');
Route::get('/update', [TableController::class, 'update'])->name('table.update');

// Route::get('/index',[TableController::class, 'index'])->name('table')->middleware(AdminMiddleware::class);
Route::get('/index',[TableController::class, 'index'])->name('table');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'process_login'])->name('process_login');
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware(AdminMiddleware::class);
Route::post('/register', [AuthController::class, 'process_register'])->name('process_register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/search', [MenuItemController::class, 'search'])->name('item.search');

Route::post('/store',[InvoiceController::class, 'store'])->name('invoice.store');

Route::get('/create', [TestController::class, 'create']);