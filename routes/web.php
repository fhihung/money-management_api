<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
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
    return view('welcome');
});


// Route lấy categories từ database
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

// Route lấy accounts từ database
Route::get('accounts/{user_id}', [AccountController::class, 'getAccountByUserId']);


// Route lấy transactions từ database
Route::get('transactions/{id}', [TransactionController::class, 'show']);
Route::get('transactions/{user_id}', [TransactionController::class, 'getTransactionByUserId']);
Route::get('transactions/{user_id}/{account_id}', [TransactionController::class, 'getTransactionByAccountId']);
Route::get('transactions/{user_id}/{category_id}', [TransactionController::class, 'getTransactionByCategoryId']);
Route::post('transactions/categories-by-date', [TransactionController::class, 'getTransactionByDateRange']);
