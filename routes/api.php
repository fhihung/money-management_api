<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('user', [App\Http\Controllers\AuthController::class, 'getAuthenticatedUser']);
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
});

Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('user', [App\Http\Controllers\AuthController::class, 'getAuthenticatedUser']);


// Route lấy categories từ database
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

// Route lấy accounts từ database
Route::get('accounts/{user_id}', [AccountController::class, 'getAccountByUserId']);

// Route lấy transactions từ database
Route::get('transactions/{id}', [TransactionController::class, 'show']);
Route::get('transactions_by_user_id', [TransactionController::class, 'getTransactionByUserId']);
Route::get('transactions/{user_id}/{account_id}', [TransactionController::class, 'getTransactionByAccountId']);
Route::post('transactions/get-by-category', [TransactionController::class, 'getTransactionByCategoryId']);
Route::post('transactions/categories-by-date', [TransactionController::class, 'getTransactionByDateRange']);
//Route::get('transactions/categories-by-date', [TransactionController::class, 'getTransactionByDate']);
