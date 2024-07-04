<?php

use Illuminate\Support\Facades\Route;

// ============================================================================>> Custom Library
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\PrintController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\UserController;

// ===========================================================================>> Dashboard
Route::get('/dashboard', [DashboardController::class, 'getDashboardInfo']);

// ===========================================================================>> POS
Route::group(['prefix' => 'pos'], function () {

    Route::get('/products', [POSController::class, 'getProducts']);
    Route::post('/order',   [POSController::class, 'makeOrder']);
});

// ===========================================================================>> Sale
Route::group(['prefix' => 'sales'], function () {

    Route::get('/',                                 [SaleController::class, 'listing']);
    Route::delete('/{id}',                          [SaleController::class, 'delete']);
    Route::get('/order-invoice/{receipt_number}',   [PrintController::class, 'printInvoiceOrder']);
    Route::get('/orderDetails/{receipt_number}',    [PrintController::class, '_getReceiptData']);
});

// ===========================================================================>> Product
Route::group(['prefix' => 'products'], function () {

    // ===>> Product Type
    Route::get('/types',        [ProductTypeController::class, 'listing']); // Read Multi Records
    Route::post('/types',       [ProductTypeController::class, 'create']); // Create New Record
    Route::post('/types/{id}',   [ProductTypeController::class, 'update']); // Update
    Route::delete('/types/{id}', [ProductTypeController::class, 'delete']); // Delete a Record

    // ===>> Product
    Route::get('/',        [ProductController::class, 'listing']); // Read Multi Records
    Route::get('/{id}',    [ProductController::class, 'view']); // View a Record
    Route::post('/',       [ProductController::class, 'create']); // Create New Record
    Route::post('/{id}',   [ProductController::class, 'update']); // Update
    Route::delete('/{id}', [ProductController::class, 'delete']); // Delete a Record
    Route::get('/transactions/{id}', [ProductController::class, 'getProduct']);
});

// ===========================================================================>> User
Route::group(['prefix' => 'users'], function () {

    Route::get('/get-type',                    [UserController::class, 'getType']);
    Route::get('/',                         [UserController::class, 'listing']); // Read Many Records
    Route::get('/{id}',                     [UserController::class, 'view']); // View a Record
    Route::post('/',                         [UserController::class, 'create']); // Create New Record
    Route::post('/{id}',                     [UserController::class, 'update']); // Update Existing Record
    Route::delete('/{id}',                     [UserController::class, 'delete']); // Delete a record

    Route::post('/block/{id}',                 [UserController::class, 'block']); // Block a user. Make sure that he/she cannot login
    Route::post('/{id}/change-password',    [UserController::class, 'changePassword']); // Change the Password

});
