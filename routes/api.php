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
Route::get('categories_by_type', [CategoryController::class, 'getCategoriesByType']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

// Route tạo category
Route::post('create_category', [CategoryController::class, 'store']);

// Route lấy accounts từ database
Route::get('accounts_by_user_id', [AccountController::class, 'getAccountByUserId']);


// Route lấy transactions từ database
Route::get('transactions_by_user_id', [TransactionController::class, 'getTransactionByUserId']);
Route::get('transactions/{user_id}/{account_id}', [TransactionController::class, 'getTransactionByAccountId']);

//Route tạo transaction
Route::post('create_transaction', [TransactionController::class, 'store']);
