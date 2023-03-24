<?php

use App\Http\Controllers\Chargeback\StoreChargebackController;
use App\Http\Controllers\Transaction\GetTransactionController;
use App\Http\Controllers\Transaction\ListTransactionController;
use App\Http\Controllers\Transaction\StoreTransactionController;
use App\Http\Controllers\User\UserListController;
use Illuminate\Support\Facades\Route;

Route::get('/users', UserListController::class)
    ->name('users');

Route::prefix('transactions')->name('transactions')->group(function () {
    Route::post('/', StoreTransactionController::class)
        ->name('.store');

    Route::get('/', ListTransactionController::class)
        ->name('.list');

    Route::get('/{id}', GetTransactionController::class)
        ->name('.get');
});

Route::prefix('chargeback')->name('chargeback')->group(function () {
    Route::post('/{transactionId}', StoreChargebackController::class)
        ->name('.store');
});
