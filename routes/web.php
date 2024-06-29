<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceApiController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Invoice;
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
Route::get('test_view', [TestController::class, 'test_view']);

Route::get('/testApi', [InvoiceApiController::class, 'index']);

Route::get('/api', [InvoiceApiController::class, 'index'])->name('api.invoices');
Route::get('/update', [TableController::class, 'update'])->name('table.update');

// Route::get('/index',[TableController::class, 'index'])->name('table')->middleware(AdminMiddleware::class);
Route::get('/index',[TableController::class, 'index'])->name('table')->middleware(AdminMiddleware::class);

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'process_login'])->name('process_login');
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware(AdminMiddleware::class);
Route::post('/register', [AuthController::class, 'process_register'])->name('process_register')->middleware(AdminMiddleware::class);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/search', [MenuItemController::class, 'search'])->name('item.search');

Route::post('/6635bb17c246e2',[InvoiceController::class, 'store'])->name('invoice.store')->middleware(AdminMiddleware::class);
Route::post('/table_update', [InvoiceController::class, 'invoice_table_update'])->name('table_update')->middleware(AdminMiddleware::class);
Route::post('/store_qr', [InvoiceController::class, 'store_qr'])->name('invoice.store_qr');
Route::post('/update_tot', [InvoiceController::class, 'update'])->name('invoice.update');
Route::get('/success', [InvoiceController::class, 'redirect_success'])->name('redirect_success');

Route::get('/create', [TestController::class, 'create']); //test

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

Route::get('/qr_order/{table_id}', [TableController::class, 'qr_show'])->name('qr.show');
