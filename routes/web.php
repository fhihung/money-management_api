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

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('user', [App\Http\Controllers\AuthController::class, 'getAuthenticatedUser']);
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
});

Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('user', [App\Http\Controllers\AuthController::class, 'getAuthenticatedUser']);


// Route lấy categories từ database
Route::get('api/categories', [CategoryController::class, 'index']);
Route::get('api/categories/{id}', [CategoryController::class, 'show']);

// Route lấy accounts từ database
Route::get('api/accounts/{user_id}', [AccountController::class, 'getAccountByUserId']);

// Route lấy transactions từ database
Route::get('api/transactions/{id}', [TransactionController::class, 'show']);
Route::get('api/transactions_by_user_id', [TransactionController::class, 'getTransactionByUserId']);
Route::get('api/transactions/{user_id}/{account_id}', [TransactionController::class, 'getTransactionByAccountId']);
Route::post('api/transactions/get-by-category', [TransactionController::class, 'getTransactionByCategoryId']);
Route::post('api/transactions/categories-by-date', [TransactionController::class, 'getTransactionByDateRange']);
//Route::get('transactions/categories-by-date', [TransactionController::class, 'getTransactionByDate']);
