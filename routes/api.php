<?php

use App\Http\Controllers\Transaction\StoreTransactionController;
use App\Http\Controllers\User\UserListController;
use Illuminate\Support\Facades\Route;

Route::get('/users', UserListController::class)
    ->name('users');

Route::prefix('transactions')->name('transactions')->group(function () {
    Route::post('/', StoreTransactionController::class)
        ->name('.store');
});
