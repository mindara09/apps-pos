<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockItemController;
use App\Http\Controllers\CategoryItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportTransactions;
use App\Http\Controllers\DashboardControllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

// Route Login
Route::controller(LoginController::class)->group(function (){
	Route::get('/login' , 'index')->name('login.index');
	Route::post('/login', 'process')->name('login.process');
	Route::get('/logout', 'logout')->name('login.logout');
});


// Route Group Middleware

Route::group(['middleware' => 'auth'], function(){

	// Route Dashboard
	Route::controller(DashboardControllers::class)->group(function(){
		Route::get('/dashboard', 'index')->name('dashboard.index');
		Route::post('/dashboard/cashin', 'cashin')->name('dashboard.cashin');
		Route::post('/dashboard/cashout', 'cashout')->name('dashboard.cashout');

	});

	// Route Transaction
	Route::controller(TransactionsController::class)->group(function() {
		Route::get('/transactions', 'index')->name('transaction.index');
		Route::get('/transactions/new', 'new_transactions')->name('transaction.new_transactions');
		Route::post('/transactions/new', 'process_transactions')->name('transaction.process_transactions');
		Route::post('/transactions/submit', 'submit_order')->name('transaction.submit_order');
		Route::delete('/transactions/delete_item/{id}', 'delete_transactions')->name('transaction.delete_transactions');
		Route::put('/transactions/update/{id}', 'update_transactions')->name('transaction.update_transactions');

		// Order
		Route::delete('/transactions/order_delete/{id}', 'delete_orders')->name('transaction.delete_orders');
		Route::get('/transactions/edit/{id}', 'edit_orders')->name('transaction.edit_orders');
		Route::post('/transactions/edit/process', 'process_edit_orders')->name('transaction.process.edit_orders');
	});

	// Route Stock Item
	Route::controller(StockItemController::class)->group(function (){
		Route::get('/stock-item','index')->name('stock.index');
		Route::delete('/stock-item/{id}', 'destroy')->name('stock.destroy');
		Route::get('/stock-item/{id}', 'edit')->name('stock.edit');
		Route::post('/stock-item', 'store')->name('stock.store');
		Route::put('/stock-item/{id}', 'update')->name('stock.update');
	});

	// Route Category Item
	Route::controller(CategoryItemController::class)->group(function() {
		Route::get('/category-item', 'index')->name('category.index');
		Route::post('/category-item', 'store')->name('category.store');
		Route::get('/category-item/{id}', 'edit')->name('category.edit');
		Route::delete('/category-item/{id}', 'destroy')->name('category.destroy');
		Route::put('/category-item/{id}', 'update')->name('category.update');
	});

	// Route User Management
	Route::controller(UserController::class)->group(function() {
		Route::get('/users', 'index')->name('users.index');
		Route::post('/users', 'store')->name('users.store');
		Route::get('/users/{id}', 'edit')->name('users.edit');
		Route::delete('/users/{id}', 'destroy')->name('users.destroy');
		Route::put('/users/{id}', 'update')->name('users.update');
		Route::post('/users-update', 'update_user')->name('update.user');
	});

	// Route Report Transactions
	Route::controller(ReportTransactions::class)->group(function() {
		Route::get('/report-transactions', 'index')->name('report.index');
		Route::post('/report-transactions', 'search')->name('report.search');
		Route::get('/report-transactions/{id}', 'pdf')->name('report.pdf');

	});

});